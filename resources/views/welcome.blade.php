<?php use App\Models\WeatherAdd;
use \Illuminate\Support\Facades\DB;







?>
<!DOCTYPE html>
<html>
<head>
    <title>Weather</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css"
          href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <style type="text/css">



        .container {
            width: 65%;
            margin: 100px auto;
        }

        .has-search .form-control-feedback {
            position: absolute;
            padding-left: 60%;
            padding-top: 5px;
            z-index: 2;
            display: block;
            width: 2.375rem;
            height: 2.375rem;
            line-height: 2.375rem;
            text-align: center;
            pointer-events: none;

        }

        .cor {
            font-size: 15px !important;
        }


    </style>

</head>
<body>
<div class="container">
    <form method="POST" action="{{route('contact-form')}}">
        @csrf
        <div class="form-group has-search">
            <br>
            <h3 class="text-center" style="font-style:Times New Roman;">Find Your Weather</h3>
            <br>
            <span class="fa fa-search form-control-feedback"></span>

            <input style="height:50px;border-width:2px !important;" name="cityName" id="cityName" type="text" class="form-control"
                   placeholder="Search City...">
            @include('messages')
            <br>
            <input type="submit" value="OK">
            <form >
                <p><select class="form-control" name="updated_at" id="updated_at">
                        <option selected value="s1">За день</option>
                        <option value="s2">За месяц</option>
                    </select>
                </p>
                <a class="p-2 text-dark" href="{{route('contact-data')}}">Посчитать</a>

                <h1>Все запросы</h1>


            </form>
        </div>
    </form>
</div>
<?php

//$DBC = DB::table('weather_adds')->orderBy('cityName', 'ASC')->get();



$bd= WeatherAdd::all()->last();
$city = $bd['cityName'];

$api_url_Ow = 'https://api.openweathermap.org/data/2.5/weather?q='.$city.'&appid=ba3906089467d72b5d15ad68a63475fc&units=metric';
$weather_data_Ow = json_decode(file_get_contents($api_url_Ow), true);
$temp_Ow = $weather_data_Ow['main']['temp'];
$lon_Ow = $weather_data_Ow['coord']['lon'];
$lat_Ow = $weather_data_Ow['coord']['lat'];



$api_url_WS = 'http://api.weatherstack.com/current?access_key=4c79d230208365f4c198c06a4affc10f&query='.$city;
$json_WS = json_decode(file_get_contents($api_url_WS), true);
$temp_WS = $json_WS['current']['temperature'];
$lon_WS = $json_WS['location']['lon'];
$lat_WS = $json_WS['location']['lat'];


?>
<div class="container">
    <div class="row">
        <div class="col-4">

            <h1>Openweather</h1>
            <h3 id="city_name"><?php echo $city?></h3>
            <h4 id="city_weather"><?php echo $temp_Ow?></h4>
            <span id="long"><?php echo $lon_Ow?></span>
            <span id="lat"><?php echo $lat_Ow?></span>
        </div>



        <div class="col-4">
            <h1>Weatherstack</h1>
            <h3 id="city_name"><?php echo $city?></h3>
            <h4 id="city_weather"><?php echo $temp_WS?></h4>
            <span id="long"><?php echo $lon_WS?></span>
            <span id="lat"><?php echo $lat_WS?></span>
        </div>
        <br>
        <h1>Средняя температура: <?php echo ($temp_Ow+$temp_WS)/2?></h1>

    </div>


</div>
</body>
</html>


