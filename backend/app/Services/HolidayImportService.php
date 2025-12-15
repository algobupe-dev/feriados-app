<?php

namespace App\Services;

use App\Models\Holiday;
use Illuminate\Support\Facades\Http;

class HolidayImportService
{
    public function import(): int
{

    $response = Http::get('https://api.boostr.cl/holidays.json');

    if (! $response->successful()) {
        throw new \Exception('Error al obtener feriados desde una API externa');
    }

    $holidays = $response->json();

    // Si la API trae metadata, entramos solo al bloque correcto
    if (isset($holidays['data']) && is_array($holidays['data'])) {
        $holidays = $holidays['data'];
    }

    $count = 0;

    /*
    |--------------------------------------------------------------------------
    | CASO 1: API devuelve array plano de feriados (estructura actual)
    |--------------------------------------------------------------------------
    */
    if (isset($holidays[0]) && is_array($holidays[0]) && isset($holidays[0]['date'])) {

        foreach ($holidays as $item) {

            Holiday::updateOrCreate(
                ['date' => $item['date']],
                [
                    'title' => $item['title'] ?? '',
                    'type' => $item['type'] ?? null,
                    'inalienable' => $item['inalienable'] ?? false,
                    'extra' => $item['extra'] ?? null,
                ]
            );

            $count++;
        }

        return $count;
    }

    /*
    |--------------------------------------------------------------------------
    | CASO 2: estructura antigua por años (fallback)
    |--------------------------------------------------------------------------
    */
    foreach ($holidays as $year => $yearHolidays) {

        if (!is_array($yearHolidays)) {
            continue;
        }

        foreach ($yearHolidays as $date => $item) {

            if (!is_array($item)) {
                continue;
            }

            Holiday::updateOrCreate(
                ['date' => $date],
                [
                    'title' => $item['title'] ?? '',
                    'type' => $item['type'] ?? null,
                    'inalienable' => $item['inalienable'] ?? false,
                    'extra' => $item['extra'] ?? null,
                ]
            );

            $count++;
        }
    }

    return $count;
}
}