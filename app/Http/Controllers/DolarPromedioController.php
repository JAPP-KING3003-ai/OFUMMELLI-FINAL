<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class DolarPromedioController extends Controller{
        public function obtenerDolarPromedio()
    {
        return Cache::remember('dolar_promedio', 3600, function () {
            try {
                // Consultar fuentes confiables
                $bcvResponse = Http::get('https://exchangemonitor.net/api/v1/rates/BCV');
                $dolarTodayResponse = Http::get('https://s3.amazonaws.com/dolartoday/data.json');
                
                // Extraer tasas de cambio
                $bcvRate = $bcvResponse->successful() && isset($bcvResponse->json()['rate']) 
                    ? $bcvResponse->json()['rate'] 
                    : null;

                $dolarTodayRate = $dolarTodayResponse->successful() && isset($dolarTodayResponse->json()['USD']['transferencia']) 
                    ? $dolarTodayResponse->json()['USD']['transferencia'] 
                    : null;

                // Calcular promedio (excluyendo nulos)
                $rates = array_filter([$bcvRate, $dolarTodayRate], fn($rate) => $rate !== null);
                $promedio = count($rates) > 0 ? array_sum($rates) / count($rates) : null;

                if ($promedio === null) {
                    throw new \Exception('No se pudo obtener suficientes tasas.');
                }

                return round($promedio, 2);
            } catch (\Exception $e) {
                return null;
            }
        });
    }
}