<?php

namespace App\Classes\WeatherApi;

class WeatherStack implements WeatherApiInterface
{
    private const API_NAME = "Open Weather Map";

    /**
     * Получение погоды из источника
     *
     * @param string $cityName          Город
     * @return WeatherApiResult         Результат апи
     * @throws ApiKeyNotFoundException  Отсутствует api key
     */
    public static function getWeather(string $cityName): WeatherApiResult
    {
        $key = env('WEATHER_STACK_API_KEY');
        if (!isset($key)) {
            throw new ApiKeyNotFoundException(self::API_NAME);
        }

        $url = "http://api.weatherstack.com/current?access_key={$key}&query={$cityName}";
        $apiResult = json_decode(file_get_contents($url), true);
        return new WeatherApiResult(
            $apiResult['location']['name'],
            $apiResult['current']['temperature'],
            $apiResult['current']['weather_descriptions'][0]
        );
    }
}
