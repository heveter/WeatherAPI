<?php

namespace App\Http\Controllers\Api\v1;

use App\Classes\WeatherApi\ApiKeyNotFoundException;
use App\Classes\WeatherApi\OpenWeatherMap;
use App\Classes\WeatherApi\WeatherStack;
use App\Classes\WeatherApi\WeatherApiResult;
use App\Http\Controllers\Controller;
use App\Models\WeatherLog;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;

class WeatherController extends Controller
{
    /*
     * Список подключенных сервисов получения погоды
     * @var array
     */
    private const API_LIST = [
        'OpenWeatherMap' => OpenWeatherMap::class,
        'WeatherStack' => WeatherStack::class,
    ];

    /**
     * Получения погоды по наименованию города
     *
     * @return JsonResponse
     */
    public function getWeather(): JsonResponse
    {
        $city = $_GET['city'];
        if (!$city) {
            return response()->json(['status' => false], 404);
        }
        $temp = 0;
        $apiCount = 0;
        foreach ($this::API_LIST as $api) {
            try {

                if (method_exists($api, 'getWeather')) {
                    /**
                     * @var WeatherApiResult
                     */
                    $weatherResult = $api::getWeather($city);
                    $temp += $weatherResult->getTemperature();
                    $apiCount++;
                }
            } catch (ApiKeyNotFoundException $exception) {
                # Сюда можно добавить обработку исключения
            } catch (Exception $exception) {
                # Сюда можно добавить обработку исключения
            }
        }
        $temp = round($temp / $apiCount, 1);
        WeatherLog::query()->create([
            'name' => $city,
            'temp' => $temp,
        ]);
        return response()->json([
            'name' => $city,
            'temp' => $temp
        ]);
    }

    /**
     * Получение списка популярных запросов
     *
     * @return JsonResponse
     */
    public function getStatistics(): JsonResponse
    {
        $date = $_GET['date'] ?? null;
        $startDate = $_GET['startDate'] ?? null;
        $endDate = $_GET['endDate'] ?? null;
        $month = $_GET['month'] ?? null;

        if (isset($date)) {
            # за определенный день
            $date = Carbon::createFromFormat('d-m-Y', $date);
            $logs = WeatherLog::query()->whereDate('created_at', $date)->get();
            $searchDate = $date->format('d-m-Y');
        } else if (isset($startDate) && isset($endDate)) {
            # в промежутке дней
            $startDate = Carbon::createFromFormat('d-m-Y H:i', $startDate . '00:00');
            $endDate = Carbon::createFromFormat('d-m-Y H:i', $endDate . '00:00');
            if ($startDate->timestamp > $endDate->timestamp) {
                return response()->json([]);
            }
            $searchDate = $startDate->format('d-m-Y') . ' - ' . $endDate->format('d-m-Y');
            $logs = WeatherLog::query()->whereBetween('created_at', [$startDate, $endDate])->get();
        } else if (isset($month)) {
            # за прошедший месяц
            $endDate = Carbon::now();
            $startDate = clone $endDate;
            $startDate->addMonths(-1);
            $searchDate = $startDate->format('d-m-Y') . ' - ' . $endDate->format('d-m-Y');
            $logs = WeatherLog::query()->whereBetween('created_at', [$startDate, $endDate])->get();
        } else {
            # за сегодняшний день
            $searchDate = Carbon::today();
            $logs = WeatherLog::query()->whereDate('created_at', $searchDate)->get();
        }

        $items = collect();
        foreach ($logs->groupBy('name') as $city => $requests) {
            $items->add([
                'city' => $city,
                'count' => count($requests)
            ]);
        }

        $items = $items->sort(function ($a, $b) {
            return $a['count'] < $b['count'];
        });

        return response()->json([
            'items' => $items,
            'dateRange' => $searchDate,
            'count' => count($logs)
        ]);
    }
}
