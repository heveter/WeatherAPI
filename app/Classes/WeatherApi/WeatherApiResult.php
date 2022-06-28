<?php

namespace App\Classes\WeatherApi;

class WeatherApiResult
{
    private string $city;
    private float $temperature;
    private string $weather;

    /**
     * @param string $city
     * @param float $temperature
     * @param string $weather
     */
    public function __construct(string $city, float $temperature, string $weather)
    {
        $this->weather = $weather;
        $this->city = $city;
        $this->temperature = $temperature;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return float
     */
    public function getTemperature(): float
    {
        return $this->temperature;
    }

    /**
     * @return string
     */
    public function getWeather(): string
    {
        return $this->weather;
    }
}
