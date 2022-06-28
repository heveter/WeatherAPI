## Установка

Для запуска необходимо установить на компьютере PHP выше 8.0 и менеджер зависимостей composer.

1. Устанавливаем через composer библиотеки
```
composer install
```

2. Создаём в корневой папке файл .env, копируем содержимое .env.example. Подставляем свои данные:

```
DB_CONNECTION=mysql
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

OPEN_WEATHER_MAP_API_KEY=
WEATHER_STACK_API_KEY=
```

3. Устанавливаем миграции

```
php artisan migrate 
```

4. Сервис готов к использованию


## Инструкция по добавлению источников

1. Создаём новый класс в папке app/Classes/WeatherApi.
2. Реализуем интерфейс WeatherApiInterface.
3. Добавляем в новый источник в массив API_LIST в контроллере Api\v1\WeatherController

```
private const API_LIST = [
    'OpenWeatherMap' => OpenWeatherMap::class,
    'WeatherStack' => WeatherStack::class,
];
```


## Инструкция по использованию

1. Получение температуры по наименованию города (из различных источников, берётся средняя температура).

Запрос:
```
<host>/api/get-weather?city=<Название города>
```

- \<host\> - адрес сервера, на котором расположен сервис.
- \<Название города\> - Наименование города. Предпочтительный язык английский

Ответ:
```
{
    "name": "<Название города>",
    "temp": 10.9
}
```


2. Получения статистики популярности городов по запросам на сервис:

Варианты запросов:
- За сегодня

```
<host>/api/get-weather-statistics
```

- За конкретное число

```
<host>/api/get-weather-statistics?date=<Дата получения статистики>
```

- За прошедший месяц

```
<host>/api/get-weather-statistics?month=true
```

- За определенный промежуток

```
<host>/api/get-weather-statistics?startDate=<Начальная дата>&endDate=<Конечная дата>
```

Конечная дата не может быть больше начальной.

Ответ:
```
{
    "items": {
        "1": {
            "city": "<Название города 1>",
            "count": 5
        },
        "3": {
            "city": "<Название города 2>",
            "count": 2
        },
        "4": {
            "city": "<Название города 3>",
            "count": 2
        },
        "0": {
            "city": "<Название города 4>",
            "count": 1
        },
        "2": {
            "city": "<Название города 5>",
            "count": 1
        }
    },
    "dateRange": "28-05-2022 - 28-06-2022",
    "count": 8
}
```
