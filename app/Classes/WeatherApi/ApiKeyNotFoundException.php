<?php

namespace App\Classes\WeatherApi;

use Exception;

class ApiKeyNotFoundException extends Exception
{
    /**
     * Наименование Api
     * @var string
     */
    private string $apiName;

    /**
     * @param string $apiName
     */
    public function __construct(string $apiName)
    {
        parent::__construct('Отсутствует ключ у api ' . $apiName);
        $this->apiName = $apiName;
    }

    /**
     * @return string
     */
    public function getApiName(): string
    {
        return $this->apiName;
    }
}
