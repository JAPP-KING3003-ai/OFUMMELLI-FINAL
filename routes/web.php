<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\CuentaController; 
use App\Http\Controllers\DolarPromedioController; // ✅ tasa de cambio
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\TicketController;

// Ruta principal
Route::get('/', function () {
    return view('welcome');
});

// Panel de Control (requiere autenticación)
Route::get('/Panel de Control', function () {
    return view('Panel de Control');
})->middleware(['auth', 'verified'])->name('Panel de Control');

// Tasa de cambio
Route::post('/api/tasa-cambio', [DolarPromedioController::class, 'actualizarTasa'])->name('tasa.actualizar');
Route::get('/api/tasa-cambio', function () {
    $tasa = DB::table('config')->where('key', 'tasa_cambio')->value('value');
    return response()->json(['tasa' => $tasa]);
});

Route::get('/cuentas/pagadas', [CuentaController::class, 'pagadas'])->name('cuentas.pagadas'); // Ver cuentas pagadas
Route::get('/cuentas/{cuenta}/imprimir/{area}', [CuentaController::class, 'imprimir'])->name('cuentas.imprimir');
Route::post('/cuentas/producto/imprimir', [TicketController::class, 'imprimirProducto'])->name('producto.imprimir');
Route::get('/cuentas/{cuenta}/imprimir/{area}', [TicketController::class, 'imprimirTicket'])->name('cuentas.imprimir');
Route::get('/cuentas/{cuenta}/imprimir-producto/{productoId}', [TicketController::class, 'imprimirProducto'])->name('cuentas.imprimir-producto');
Route::post('/productos/marcar-impreso', [TicketController::class, 'marcarProductoImpreso']);




// Agrupadas bajo autenticación
Route::middleware('auth')->group(function () {

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Inventarios
    Route::get('/inventarios', [InventarioController::class, 'index'])->name('inventarios.index');
    Route::get('/inventarios/create', [InventarioController::class, 'create'])->name('inventarios.create'); // Crear producto
    Route::post('/inventarios', [InventarioController::class, 'store'])->name('inventarios.store'); // Guardar producto
    Route::get('/inventarios/{id}/edit', [InventarioController::class, 'edit'])->name('inventarios.edit'); // Editar producto
    Route::put('/inventarios/{id}', [InventarioController::class, 'update'])->name('inventarios.update'); // Actualizar producto
    Route::get('/inventarios/entrada/{id}', [InventarioController::class, 'entrada'])->name('inventarios.entrada'); // Registrar entrada individual
    Route::post('/inventarios/entrada/{id}', [InventarioController::class, 'storeEntrada'])->name('inventarios.storeEntrada'); // Guardar entrada individual
    Route::get('/inventarios/entrada-global', [InventarioController::class, 'entradaGlobal'])->name('inventarios.entrada.global'); // Registrar entrada global
    Route::post('/inventarios/entrada-global', [InventarioController::class, 'storeEntradaGlobal'])->name('inventarios.entrada.global.store'); // Guardar entrada global
    Route::get('/inventarios/exportar', [InventarioController::class, 'exportarExcel'])->name('inventarios.exportar'); // Exportar inventario
    Route::delete('/inventarios/{id}', [InventarioController::class, 'destroy'])->name('inventarios.destroy'); // Eliminar producto
    Route::post('/inventarios', [InventarioController::class, 'store'])->name('inventarios.store'); // Guardar producto

    // Movimientos
    Route::get('/movimientos', [MovimientoController::class, 'index'])->name('movimientos.index');
    Route::delete('/movimientos/{movimiento}', [MovimientoController::class, 'destroy'])->name('movimientos.destroy');
    // Productos
    Route::resource('productos', ProductoController::class); // CRUD completo para productos
    Route::get('/productos/buscar', [ProductoController::class, 'buscar'])->name('productos.buscar'); // Buscar producto

    // Clientes
    Route::resource('clientes', ClienteController::class); // CRUD completo para clientes
    Route::get('/clientes/buscar', [ClienteController::class, 'buscar'])->name('clientes.buscar'); // Buscar cliente
    Route::get('/clientes/exportar', [ClienteController::class, 'exportarExcel'])->name('clientes.exportar'); // Exportar clientes

    // Cuentas
    Route::resource('cuentas', CuentaController::class); // CRUD completo para cuentas
    Route::get('/cuentas/pagadas', [CuentaController::class, 'pagadas'])->name('cuentas.pagadas'); // Ver cuentas pagadas
    Route::get('/cuentas/exportar-pagadas', [CuentaController::class, 'exportarCuentasPagadas'])->name('cuentas.exportarPagadas'); // Exportar cuentas pagadas
    Route::put('/cuentas/{cuenta}/cerrar', [CuentaController::class, 'cerrar'])->name('cuentas.cerrar'); // Cerrar cuenta
    Route::post('/cuentas/{cuenta}/marcar-pagada', [CuentaController::class, 'marcarPagada'])->name('cuentas.marcarPagada'); // Marcar cuenta como pagada
    
    // Ruta para imprimir el ticket
    Route::get('/cuentas/{cuenta}/imprimir/{area}', [TicketController::class, 'imprimirTicket'])->name('cuentas.imprimir');});

// Dólar promedio (ruta pública)
Route::get('/dolar-promedio', [DolarPromedioController::class, 'obtenerDolarPromedio']);

require __DIR__.'/auth.php';