<?php

namespace App\Classes\WeatherApi;

interface WeatherApiInterface
{
    /**
     * Получение погоды из источника
     *
     * @param string $cityName          Город
     * @return WeatherApiResult         Результат апи
     * @throws ApiKeyNotFoundException  Отсутствует api key
     */
    public static function getWeather(string $cityName,): WeatherApiResult;
}

