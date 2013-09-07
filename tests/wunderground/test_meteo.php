<?php

$meteo_api_key = 'a3377ceb5414edef';

$meteo_api_enpoint = "http://api.wunderground.com/api/${meteo_api_key}/conditions/q/";
$meteo_query_url = $meteo_api_enpoint . 'CA/San_Francisco.json';

$forecast_api_endpoint = "http://api.wunderground.com/api/${meteo_api_key}/forecast/q/";
$forecast_query_url = $forecast_api_endpoint . 'CA/San_Francisco.json';

$json_meteo = file_get_contents($meteo_query_url);
/*$json_meteo = '{
	"response": {
		"version": "0.1"
		,"termsofService": "http://www.wunderground.com/weather/api/d/terms.html"
		,"features": {
		"conditions": 1
		}
	}
		,	"current_observation": {
		"image": {
		"url":"http://icons-ak.wxug.com/graphics/wu2/logo_130x80.png",
		"title":"Weather Underground",
		"link":"http://www.wunderground.com"
		},
		"display_location": {
		"full":"San Francisco, CA",
		"city":"San Francisco",
		"state":"CA",
		"state_name":"California",
		"country":"US",
		"country_iso3166":"US",
		"zip":"94101",
		"magic":"1",
		"wmo":"99999",
		"latitude":"37.77500916",
		"longitude":"-122.41825867",
		"elevation":"47.00000000"
		},
		"observation_location": {
		"full":"SOMA - Near Van Ness, San Francisco, California",
		"city":"SOMA - Near Van Ness, San Francisco",
		"state":"California",
		"country":"US",
		"country_iso3166":"US",
		"latitude":"37.773285",
		"longitude":"-122.417725",
		"elevation":"49 ft"
		},
		"estimated": {
		},
		"station_id":"KCASANFR58",
		"observation_time":"Last Updated on September 6, 8:24 PM PDT",
		"observation_time_rfc822":"Fri, 06 Sep 2013 20:24:26 -0700",
		"observation_epoch":"1378524266",
		"local_time_rfc822":"Fri, 06 Sep 2013 20:24:47 -0700",
		"local_epoch":"1378524287",
		"local_tz_short":"PDT",
		"local_tz_long":"America/Los_Angeles",
		"local_tz_offset":"-0700",
		"weather":"Clear",
		"temperature_string":"73.0 F (22.8 C)",
		"temp_f":73.0,
		"temp_c":22.8,
		"relative_humidity":"52%",
		"wind_string":"Calm",
		"wind_dir":"SE",
		"wind_degrees":131,
		"wind_mph":0.0,
		"wind_gust_mph":0,
		"wind_kph":0.0,
		"wind_gust_kph":0,
		"pressure_mb":"1010",
		"pressure_in":"29.83",
		"pressure_trend":"0",
		"dewpoint_string":"54 F (12 C)",
		"dewpoint_f":54,
		"dewpoint_c":12,
		"heat_index_string":"NA",
		"heat_index_f":"NA",
		"heat_index_c":"NA",
		"windchill_string":"NA",
		"windchill_f":"NA",
		"windchill_c":"NA",
		"feelslike_string":"73.0 F (22.8 C)",
		"feelslike_f":"73.0",
		"feelslike_c":"22.8",
		"visibility_mi":"10.0",
		"visibility_km":"16.1",
		"solarradiation":"",
		"UV":"0","precip_1hr_string":"0.00 in ( 0 mm)",
		"precip_1hr_in":"0.00",
		"precip_1hr_metric":" 0",
		"precip_today_string":"0.00 in (0 mm)",
		"precip_today_in":"0.00",
		"precip_today_metric":"0",
		"icon":"clear",
		"icon_url":"http://icons-ak.wxug.com/i/c/k/nt_clear.gif",
		"forecast_url":"http://www.wunderground.com/US/CA/San_Francisco.html",
		"history_url":"http://www.wunderground.com/weatherstation/WXDailyHistory.asp?ID=KCASANFR58",
		"ob_url":"http://www.wunderground.com/cgi-bin/findweather/getForecast?query=37.773285,-122.417725"
	}
}
';*/

$meteo = json_decode($json_meteo);

$json_forecast = file_get_contents($forecast_query_url);
/*$json_forecast = '{
	"response": {
		"version": "0.1"
		,"termsofService": "http://www.wunderground.com/weather/api/d/terms.html"
		,"features": {
		"forecast": 1
		}
	}
		,
	"forecast":{
		"txt_forecast": {
		"date":"8:00 PM PDT",
		"forecastday": [
		{
		"period":0,
		"icon":"partlycloudy",
		"icon_url":"http://icons-ak.wxug.com/i/c/k/partlycloudy.gif",
		"title":"Friday",
		"fcttext":"Clear. High of 79F. Winds from the NNE at 5 to 15 mph shifting to the West in the afternoon.",
		"fcttext_metric":"Clear. High of 26C. Winds from the NNE at 5 to 20 km/h shifting to the West in the afternoon.",
		"pop":"0"
		}
		,
		{
		"period":1,
		"icon":"clear",
		"icon_url":"http://icons-ak.wxug.com/i/c/k/clear.gif",
		"title":"Friday Night",
		"fcttext":"Clear. Low of 59F. Winds less than 5 mph.",
		"fcttext_metric":"Clear. Low of 15C. Winds less than 5 km/h.",
		"pop":"0"
		}
		,
		{
		"period":2,
		"icon":"clear",
		"icon_url":"http://icons-ak.wxug.com/i/c/k/clear.gif",
		"title":"Saturday",
		"fcttext":"Clear. High of 81F. Winds from the West at 5 to 15 mph.",
		"fcttext_metric":"Clear. High of 27C. Breezy. Winds from the West at 5 to 25 km/h.",
		"pop":"0"
		}
		,
		{
		"period":3,
		"icon":"clear",
		"icon_url":"http://icons-ak.wxug.com/i/c/k/clear.gif",
		"title":"Saturday Night",
		"fcttext":"Clear in the evening, then partly cloudy. Low of 59F. Winds from the WSW at 5 to 10 mph.",
		"fcttext_metric":"Clear in the evening, then partly cloudy. Low of 15C. Winds from the WSW at 10 to 15 km/h.",
		"pop":"0"
		}
		,
		{
		"period":4,
		"icon":"partlycloudy",
		"icon_url":"http://icons-ak.wxug.com/i/c/k/partlycloudy.gif",
		"title":"Sunday",
		"fcttext":"Partly cloudy in the morning, then clear. High of 86F. Winds from the WSW at 5 to 10 mph.",
		"fcttext_metric":"Partly cloudy in the morning, then clear. High of 30C. Winds from the WSW at 10 to 15 km/h.",
		"pop":"0"
		}
		,
		{
		"period":5,
		"icon":"clear",
		"icon_url":"http://icons-ak.wxug.com/i/c/k/clear.gif",
		"title":"Sunday Night",
		"fcttext":"Partly cloudy. Low of 61F. Winds from the SW at 5 to 10 mph.",
		"fcttext_metric":"Partly cloudy. Low of 16C. Winds from the SW at 10 to 15 km/h.",
		"pop":"0"
		}
		,
		{
		"period":6,
		"icon":"clear",
		"icon_url":"http://icons-ak.wxug.com/i/c/k/clear.gif",
		"title":"Monday",
		"fcttext":"Clear. High of 84F. Winds from the SW at 5 to 15 mph.",
		"fcttext_metric":"Clear. High of 29C. Breezy. Winds from the SW at 10 to 20 km/h.",
		"pop":"0"
		}
		,
		{
		"period":7,
		"icon":"partlycloudy",
		"icon_url":"http://icons-ak.wxug.com/i/c/k/partlycloudy.gif",
		"title":"Monday Night",
		"fcttext":"Partly cloudy. Low of 63F. Winds less than 5 mph.",
		"fcttext_metric":"Partly cloudy. Low of 17C. Winds less than 5 km/h.",
		"pop":"0"
		}
		]
		},
		"simpleforecast": {
		"forecastday": [
		{"date":{
	"epoch":"1378533600",
	"pretty":"11:00 PM PDT on September 06, 2013",
	"day":6,
	"month":9,
	"year":2013,
	"yday":248,
	"hour":23,
	"min":"00",
	"sec":0,
	"isdst":"1",
	"monthname":"September",
	"weekday_short":"Fri",
	"weekday":"Friday",
	"ampm":"PM",
	"tz_short":"PDT",
	"tz_long":"America/Los_Angeles"
},
		"period":1,
		"high": {
		"fahrenheit":"79",
		"celsius":"26"
		},
		"low": {
		"fahrenheit":"59",
		"celsius":"15"
		},
		"conditions":"Partly Cloudy",
		"icon":"partlycloudy",
		"icon_url":"http://icons-ak.wxug.com/i/c/k/partlycloudy.gif",
		"skyicon":"sunny",
		"pop":0,
		"qpf_allday": {
		"in": 0.00,
		"mm": 0.0
		},
		"qpf_day": {
		"in": 0.00,
		"mm": 0.0
		},
		"qpf_night": {
		"in": 0.00,
		"mm": 0.0
		},
		"snow_allday": {
		"in": 0,
		"cm": 0
		},
		"snow_day": {
		"in": 0,
		"cm": 0
		},
		"snow_night": {
		"in": 0,
		"cm": 0
		},
		"maxwind": {
		"mph": 12,
		"kph": 19,
		"dir": "West",
		"degrees": 275
		},
		"avewind": {
		"mph": 9,
		"kph": 14,
		"dir": "West",
		"degrees": 267
		},
		"avehumidity": 65,
		"maxhumidity": 88,
		"minhumidity": 45
		}
		,
		{"date":{
	"epoch":"1378620000",
	"pretty":"11:00 PM PDT on September 07, 2013",
	"day":7,
	"month":9,
	"year":2013,
	"yday":249,
	"hour":23,
	"min":"00",
	"sec":0,
	"isdst":"1",
	"monthname":"September",
	"weekday_short":"Sat",
	"weekday":"Saturday",
	"ampm":"PM",
	"tz_short":"PDT",
	"tz_long":"America/Los_Angeles"
},
		"period":2,
		"high": {
		"fahrenheit":"81",
		"celsius":"27"
		},
		"low": {
		"fahrenheit":"59",
		"celsius":"15"
		},
		"conditions":"Clear",
		"icon":"clear",
		"icon_url":"http://icons-ak.wxug.com/i/c/k/clear.gif",
		"skyicon":"sunny",
		"pop":0,
		"qpf_allday": {
		"in": 0.00,
		"mm": 0.0
		},
		"qpf_day": {
		"in": 0.00,
		"mm": 0.0
		},
		"qpf_night": {
		"in": 0.00,
		"mm": 0.0
		},
		"snow_allday": {
		"in": 0,
		"cm": 0
		},
		"snow_day": {
		"in": 0,
		"cm": 0
		},
		"snow_night": {
		"in": 0,
		"cm": 0
		},
		"maxwind": {
		"mph": 13,
		"kph": 21,
		"dir": "WSW",
		"degrees": 254
		},
		"avewind": {
		"mph": 9,
		"kph": 14,
		"dir": "West",
		"degrees": 262
		},
		"avehumidity": 66,
		"maxhumidity": 81,
		"minhumidity": 37
		}
		,
		{"date":{
	"epoch":"1378706400",
	"pretty":"11:00 PM PDT on September 08, 2013",
	"day":8,
	"month":9,
	"year":2013,
	"yday":250,
	"hour":23,
	"min":"00",
	"sec":0,
	"isdst":"1",
	"monthname":"September",
	"weekday_short":"Sun",
	"weekday":"Sunday",
	"ampm":"PM",
	"tz_short":"PDT",
	"tz_long":"America/Los_Angeles"
},
		"period":3,
		"high": {
		"fahrenheit":"86",
		"celsius":"30"
		},
		"low": {
		"fahrenheit":"61",
		"celsius":"16"
		},
		"conditions":"Partly Cloudy",
		"icon":"partlycloudy",
		"icon_url":"http://icons-ak.wxug.com/i/c/k/partlycloudy.gif",
		"skyicon":"mostlysunny",
		"pop":0,
		"qpf_allday": {
		"in": 0.00,
		"mm": 0.0
		},
		"qpf_day": {
		"in": 0.00,
		"mm": 0.0
		},
		"qpf_night": {
		"in": 0.00,
		"mm": 0.0
		},
		"snow_allday": {
		"in": 0,
		"cm": 0
		},
		"snow_day": {
		"in": 0,
		"cm": 0
		},
		"snow_night": {
		"in": 0,
		"cm": 0
		},
		"maxwind": {
		"mph": 9,
		"kph": 14,
		"dir": "West",
		"degrees": 261
		},
		"avewind": {
		"mph": 8,
		"kph": 13,
		"dir": "WSW",
		"degrees": 251
		},
		"avehumidity": 65,
		"maxhumidity": 88,
		"minhumidity": 42
		}
		,
		{"date":{
	"epoch":"1378792800",
	"pretty":"11:00 PM PDT on September 09, 2013",
	"day":9,
	"month":9,
	"year":2013,
	"yday":251,
	"hour":23,
	"min":"00",
	"sec":0,
	"isdst":"1",
	"monthname":"September",
	"weekday_short":"Mon",
	"weekday":"Monday",
	"ampm":"PM",
	"tz_short":"PDT",
	"tz_long":"America/Los_Angeles"
},
		"period":4,
		"high": {
		"fahrenheit":"84",
		"celsius":"29"
		},
		"low": {
		"fahrenheit":"63",
		"celsius":"17"
		},
		"conditions":"Clear",
		"icon":"clear",
		"icon_url":"http://icons-ak.wxug.com/i/c/k/clear.gif",
		"skyicon":"sunny",
		"pop":0,
		"qpf_allday": {
		"in": 0.00,
		"mm": 0.0
		},
		"qpf_day": {
		"in": 0.00,
		"mm": 0.0
		},
		"qpf_night": {
		"in": 0.00,
		"mm": 0.0
		},
		"snow_allday": {
		"in": 0,
		"cm": 0
		},
		"snow_day": {
		"in": 0,
		"cm": 0
		},
		"snow_night": {
		"in": 0,
		"cm": 0
		},
		"maxwind": {
		"mph": 11,
		"kph": 18,
		"dir": "WSW",
		"degrees": 240
		},
		"avewind": {
		"mph": 9,
		"kph": 14,
		"dir": "WSW",
		"degrees": 238
		},
		"avehumidity": 58,
		"maxhumidity": 82,
		"minhumidity": 44
		}
		]
		}
	}
}';*/
$forecast = json_decode($json_forecast);
?>

<H1>NOW</h1>
<?php echo $meteo->current_observation->display_location->city; ?>
<img src="<?php echo $meteo->current_observation->icon_url; ?>">
<?php echo $meteo->current_observation->temperature_string; ?>

<H1>FORECAST</h1>
<?php
foreach ($forecast->forecast->txt_forecast->forecastday as $d)
{
?>

<h2><?php echo $d->title;  ?></h2>
<img src="<?php echo $d->icon_url ?>">
<?php echo $d->fcttext; ?>
<?php
}
?>
