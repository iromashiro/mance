<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherProxyController extends Controller
{
    public function weather(Request $req)
    {
        $lat = (float) $req->query('lat', -3.731);
        $lng = (float) $req->query('lng', 103.835);
        $key = env('GOOGLE_MAPS_KEY');

        // ==== 1) Google Weather: currentConditions:lookup (skema TOP-LEVEL) ====
        $gc = [];
        if ($key) {
            $gcResp = Http::timeout(10)->get('https://weather.googleapis.com/v1/currentConditions:lookup', [
                'key'                 => $key,
                'location.latitude'   => $lat,
                'location.longitude'  => $lng,
                'unitsSystem'         => 'METRIC',
                'languageCode'        => 'id',
            ]);
            if ($gcResp->ok()) {
                $gc = $gcResp->json();
            }
        }

        // Ambil nilai dari skema top-level Google Weather (dengan fallback)
        $temp   = self::num(data_get($gc, 'temperature.degrees', data_get($gc, 'temperature')));
        $feels  = self::num(data_get($gc, 'feelsLikeTemperature.degrees', data_get($gc, 'feelsLikeTemperature')));
        $hum    = self::num(data_get($gc, 'relativeHumidity')); // sudah 0..100
        $uv     = self::num(data_get($gc, 'uvIndex'));

        $windV  = self::num(data_get($gc, 'wind.speed.value', data_get($gc, 'windSpeed.value', data_get($gc, 'windSpeed'))));
        $windU  = strtoupper(data_get($gc, 'wind.speed.unit', data_get($gc, 'windSpeed.unit', 'KM_PER_HOUR')));

        $text   = data_get($gc, 'weatherCondition.description.text')
            ?: data_get($gc, 'weatherCondition.type')
            ?: 'Kondisi terkini';

        // sunrise/sunset tidak tersedia di currentConditions:lookup → biarkan null dulu
        $sunrise = null;
        $sunset  = null;

        // ==== 2) Fallback jika ada nilai yang kosong → Open-Meteo (no key) ====
        if (is_null($temp) || is_null($feels) || is_null($hum) || is_null($windV) || is_null($uv) || is_null($sunrise) || is_null($sunset)) {
            $om = Http::timeout(10)->get('https://api.open-meteo.com/v1/forecast', [
                'latitude'  => $lat,
                'longitude' => $lng,
                'current'   => 'temperature_2m,apparent_temperature,relative_humidity_2m,wind_speed_10m,uv_index,weather_code',
                'daily'     => 'sunrise,sunset',
                'timezone'  => 'auto',
            ]);

            if ($om->ok()) {
                $j = $om->json();
                $curr  = $j['current'] ?? [];
                $daily = $j['daily'] ?? [];

                $temp   = $temp   ?? self::num($curr['temperature_2m'] ?? null);
                $feels  = $feels  ?? self::num($curr['apparent_temperature'] ?? null);
                $hum    = $hum    ?? self::num($curr['relative_humidity_2m'] ?? null);
                $windV  = $windV  ?? self::num($curr['wind_speed_10m'] ?? null); // km/h
                $uv     = $uv     ?? self::num($curr['uv_index'] ?? null);
                $text   = $text   ?: self::wmoText($curr['weather_code'] ?? null) ?: 'Kondisi terkini';
                $sunrise = $sunrise ?: ($daily['sunrise'][0] ?? null);
                $sunset  = $sunset  ?: ($daily['sunset'][0]  ?? null);
                $windU   = 'KM_PER_HOUR';
            }
        }

        // Konversi unit angin kalau dari Google unit m/s
        if (!is_null($windV) && $windU === 'METER_PER_SECOND') {
            $windV = $windV * 3.6; // m/s -> km/h
            $windU = 'KM_PER_HOUR';
        }

        // ==== 3) Nama tempat (reverse geocode) jika kosong ====
        $placeName = null;
        if ($key) {
            $geo = Http::timeout(10)->get('https://maps.googleapis.com/maps/api/geocode/json', [
                'latlng'   => $lat . ',' . $lng,
                'key'      => $key,
                'language' => 'id',
            ]);
            if ($geo->ok()) {
                $placeName = $geo->json('results.0.formatted_address') ?: null;
            }
        }

        // ==== 4) Bentuk payload untuk frontend ====
        $normalized = [
            'place' => [
                'name'              => $placeName,
                'formattedAddress'  => $placeName,
            ],
            'currentConditions' => [
                'temperature'         => $temp,
                'temperatureApparent' => $feels,
                'humidity'            => $hum,
                'uvIndex'             => $uv,
                'weatherCondition'    => ['text' => $text],
                'wind'                => ['speed' => ['value' => $windV, 'unit' => 'KM_PER_HOUR']],
                'sunriseTime'         => $sunrise,
                'sunsetTime'          => $sunset,
            ],
        ];

        return response()
            ->json($normalized)
            ->header('Cache-Control', 'public, max-age=300');
    }

    public function air(Request $req)
    {
        $lat = (float) $req->query('lat', -3.731);
        $lng = (float) $req->query('lng', 103.835);
        $key = env('GOOGLE_WEATHER_KEY', env('GOOGLE_MAPS_KEY'));

        $payload = [
            'location'          => ['latitude' => $lat, 'longitude' => $lng],
            'languageCode'      => 'id',
            'universalAqi'      => true,
            'extraComputations' => ['POLLUTANT_CONCENTRATION', 'HEALTH_RECOMMENDATIONS'],
        ];

        $resp = Http::timeout(10)
            ->withHeaders(['X-Goog-FieldMask' => 'indexes,healthRecommendations'])
            ->post('https://airquality.googleapis.com/v1/currentConditions:lookup?key=' . $key, $payload);

        if (!$resp->ok()) {
            return response()->json($resp->json(), $resp->status());
        }

        return response()
            ->json($resp->json())
            ->header('Cache-Control', 'public, max-age=300');
    }

    private static function num($v)
    {
        return is_numeric($v) ? (float)$v : null;
    }

    private static function wmoText($code)
    {
        $map = [
            0 => 'Cerah',
            1 => 'Cerah berawan',
            2 => 'Berawan sebagian',
            3 => 'Berawan',
            45 => 'Berkabut',
            48 => 'Kabut membeku',
            51 => 'Gerimis ringan',
            53 => 'Gerimis',
            55 => 'Gerimis lebat',
            61 => 'Hujan ringan',
            63 => 'Hujan sedang',
            65 => 'Hujan lebat',
            66 => 'Hujan beku ringan',
            67 => 'Hujan beku lebat',
            71 => 'Salju ringan',
            73 => 'Salju',
            75 => 'Salju lebat',
            77 => 'Butiran salju',
            80 => 'Hujan deras sporadis',
            81 => 'Hujan deras',
            82 => 'Hujan sangat deras',
            85 => 'Salju sporadis',
            86 => 'Salju lebat sporadis',
            95 => 'Badai petir',
            96 => 'Badai petir + es ringan',
            99 => 'Badai petir + es lebat',
        ];
        return $map[$code] ?? null;
    }
}
