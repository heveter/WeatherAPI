<?php

namespace App\Classes\WeatherApi;

class OpenWeatherMap implements WeatherApiInterface
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
        $key = env("OPEN_WEATHER_MAP_API_KEY");
        if (!isset($key)) {
            throw new ApiKeyNotFoundException(self::API_NAME);
        }
        $url = "https://api.openweathermap.org/data/2.5/weather?q={$cityName}&appid={$key}&units=metric";
        $apiResult = json_decode(file_get_contents($url), true);
        return new WeatherApiResult(
            $apiResult['name'],
            $apiResult['main']['temp'],
            $apiResult['weather'][0]['main'] ?? ''
        );
    }
}
