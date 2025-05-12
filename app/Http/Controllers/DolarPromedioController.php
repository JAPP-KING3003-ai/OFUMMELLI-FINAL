<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// class DolarPromedioController extends Controller{
//         public function obtenerDolarPromedio()
//     {
//         return Cache::remember('dolar_promedio', 3600, function () {
//             try {
//                 // Consultar fuentes confiables
//                 $bcvResponse = Http::get('https://exchangemonitor.net/api/v1/rates/BCV');
//                 $dolarTodayResponse = Http::get('https://s3.amazonaws.com/dolartoday/data.json');
                
//                 // Extraer tasas de cambio
//                 $bcvRate = $bcvResponse->successful() && isset($bcvResponse->json()['rate']) 
//                     ? $bcvResponse->json()['rate'] 
//                     : null;

//                 $dolarTodayRate = $dolarTodayResponse->successful() && isset($dolarTodayResponse->json()['USD']['transferencia']) 
//                     ? $dolarTodayResponse->json()['USD']['transferencia'] 
//                     : null;

//                 // Calcular promedio (excluyendo nulos)
//                 $rates = array_filter([$bcvRate, $dolarTodayRate], fn($rate) => $rate !== null);
//                 $promedio = count($rates) > 0 ? array_sum($rates) / count($rates) : null;

//                 if ($promedio === null) {
//                     throw new \Exception('No se pudo obtener suficientes tasas.');
//                 }

//                 return round($promedio, 2);
//             } catch (\Exception $e) {
//                 return null;
//             }
//         });
//     }
// }

class DolarPromedioController extends Controller
{
    public function actualizarTasa(Request $request)
    {
        $request->validate([
            'tasa' => 'required|numeric|min:0',
        ]);

        // Actualizar la tasa en la base de datos
        DB::table('config')->updateOrInsert(
            ['key' => 'tasa_cambio'],
            ['value' => $request->tasa]
        );

        // Flash message con la nueva tasa
    session()->flash('success', "Se ha actualizado exitosamente la tasa a {$request->tasa}.");


        return redirect()->back()->with('success', 'La tasa de cambio se ha actualizado correctamente.');
    }
}