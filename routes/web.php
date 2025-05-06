<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\CuentaController; 
use App\Http\Controllers\DolarPromedioController; // ✅ tasa de cambio

// Ruta principal
Route::get('/', function () {
    return view('welcome');
});

// Dashboard (requiere autenticación)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Agrupadas bajo autenticación
Route::middleware('auth')->group(function () {

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Inventarios
    Route::get('/inventarios/entrada-global', [InventarioController::class, 'entradaGlobal'])->name('inventarios.entrada.global');
    Route::post('/inventarios/entrada-global', [InventarioController::class, 'storeEntradaGlobal'])->name('inventarios.entrada.global.store');
    Route::get('/inventarios/entrada/{id}', [InventarioController::class, 'entrada'])->name('inventarios.entrada');
    Route::post('/inventarios/entrada/{id}', [InventarioController::class, 'storeEntrada'])->name('inventarios.storeEntrada');
    Route::get('/inventarios', [InventarioController::class, 'index'])->name('inventarios.index');

    // Movimientos
    Route::get('/movimientos', [MovimientoController::class, 'index'])->name('movimientos.index');

    // Productos
    Route::resource('productos', ProductoController::class);
    Route::get('/productos/buscar', [ProductoController::class, 'buscar'])->name('productos.buscar');

    // Exportar Excel
    Route::get('/clientes/exportar', [ClienteController::class, 'exportarExcel'])->name('clientes.exportar');
    Route::get('/cuentas/exportar-pagadas', [CuentaController::class, 'exportarCuentasPagadas'])->name('cuentas.exportarPagadas');

    // Clientes
    Route::resource('clientes', ClienteController::class);
    Route::get('/clientes/buscar', [ClienteController::class, 'buscar'])->name('clientes.buscar');

    // Cuentas (nuevo módulo)
    Route::resource('cuentas', CuentaController::class);
    Route::put('/cuentas/{cuenta}/cerrar', [CuentaController::class, 'cerrar'])->name('cuentas.cerrar');
    Route::post('/cuentas/{cuenta}/marcar-pagada', [CuentaController::class, 'marcarPagada'])->name('cuentas.marcarPagada');
    Route::get('/cuentas', [CuentaController::class, 'index'])->name('cuentas.index');
    Route::get('/cuentas/pagadas', [CuentaController::class, 'pagadas'])->name('cuentas.pagadas');
    Route::get('/cuentas/{cuenta}/edit', [CuentaController::class, 'edit'])->name('cuentas.edit');

    // Tasa de cambio
    Route::get('/api/tasa-cambio', function () {
        // Ejemplo: Devuelve una tasa fija o consulta una API externa
        return response()->json(['tasa' => 100]); // Sustituye por tu lógica real
    });

    Route::get('/dolar-promedio', [DolarPromedioController::class, 'obtenerDolarPromedio']);
});

require __DIR__.'/auth.php';