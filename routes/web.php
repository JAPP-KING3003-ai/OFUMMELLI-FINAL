<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\CuentaController;
use App\Http\Controllers\DolarPromedioController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\DB;

// RUTA DE LOGIN PERSONALIZADA (debe estar antes de cualquier require y solo debe haber una)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/tickets/imprimir/{cuenta}/{idUnico}', [TicketController::class, 'imprimirQZ'])->name('tickets.imprimirQZ');
Route::get('/cuentas/exportar-pagadas', [CuentaController::class, 'exportarCuentasPagadas'])->name('cuentas.exportarPagadas');
Route::get('cuentas/{cuenta}/imprimir-factura', [\App\Http\Controllers\CuentaController::class, 'imprimirFactura'])->name('cuentas.imprimirFactura');


// RUTA PRINCIPAL
Route::get('/', function () {
    return view('welcome');
});

// PANEL DE CONTROL (protegido)
Route::get('/Panel de Control', function () {
    return view('Panel de Control');
})->middleware(['auth', 'verified'])->name('Panel de Control');

// TASA DE CAMBIO (APIs)
Route::post('/api/tasa-cambio', [DolarPromedioController::class, 'actualizarTasa'])->name('tasa.actualizar');
Route::get('/api/tasa-cambio', function () {
    $tasa = DB::table('config')->where('key', 'tasa_cambio')->value('value');
    return response()->json(['tasa' => $tasa]);
});

// DÓLAR PROMEDIO (pública)
Route::get('/dolar-promedio', [DolarPromedioController::class, 'obtenerDolarPromedio']);

// RUTAS PARA IMPRESIÓN DE TICKETS Y MARCAR IMPRESO (solo una vez, sin duplicados)
Route::post('/cuentas/{cuenta}/producto/{productoId}/{idUnico}/imprimir', [TicketController::class, 'imprimirProducto'])->name('cuenta.imprimir.producto');
Route::post('/cuentas/{cuentaId}/imprimir', [TicketController::class, 'imprimir'])->name('cuenta.imprimir');
Route::post('/producto/marcar-impreso', [TicketController::class, 'marcarProductoImpreso'])->name('producto.marcar.impreso');
Route::post('/productos/marcar-impreso', [TicketController::class, 'marcarProductoImpreso']); // Si usas ambas rutas, unifícalas en una sola

// RUTAS DE CUENTAS Y TICKETS QUE NO REQUIEREN AUTH (AJUSTA SI ES NECESARIO)
Route::get('/cuentas/pagadas', [CuentaController::class, 'pagadas'])->name('cuentas.pagadas');
Route::get('/cuentas/{cuenta}/imprimir/{area}', [CuentaController::class, 'imprimir'])->name('cuentas.imprimir');
Route::get('/cuentas/{cuenta}/imprimir-producto/{productoId}/{idUnico}', [TicketController::class, 'imprimirProducto'])->name('cuentas.imprimir-producto');
Route::get('/cuentas/{cuenta}/imprimir-linea/{lineId}', [TicketController::class, 'imprimirLinea'])->name('cuentas.imprimir-linea');

// INVENTARIOS (AJUSTA LAS QUE NO REQUIEREN AUTH)
Route::get('/inventarios/{id}/lotes', [InventarioController::class, 'show'])->name('inventarios.lotes');
Route::post('/inventarios', [InventarioController::class, 'store'])->name('inventarios.store');
Route::post('/inventarios/entrada/{id}', [InventarioController::class, 'storeEntrada'])->name('inventarios.storeEntrada');
Route::put('/inventarios/{id}', [InventarioController::class, 'update'])->name('inventarios.update');
Route::get('/inventarios/{id}/salida', [InventarioController::class, 'formSalida'])->name('inventarios.salida.form');
Route::post('/inventarios/{id}/salida', [InventarioController::class, 'registrarSalida'])->name('inventarios.salida');

// AGRUPADAS BAJO AUTENTICACIÓN
Route::middleware('auth')->group(function () {

    // PERFIL
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // INVENTARIOS (CRUD COMPLETO Y ENTRADAS)
    Route::get('/inventarios', [InventarioController::class, 'index'])->name('inventarios.index');
    Route::get('/inventarios/create', [InventarioController::class, 'create'])->name('inventarios.create');
    Route::post('/inventarios', [InventarioController::class, 'store'])->name('inventarios.store');
    Route::get('/inventarios/{id}/edit', [InventarioController::class, 'edit'])->name('inventarios.edit');
    Route::put('/inventarios/{id}', [InventarioController::class, 'update'])->name('inventarios.update');
    Route::get('/inventarios/entrada/{id}', [InventarioController::class, 'entrada'])->name('inventarios.entrada');
    Route::post('/inventarios/entrada/{id}', [InventarioController::class, 'storeEntrada'])->name('inventarios.storeEntrada');
    Route::get('/inventarios/entrada-global', [InventarioController::class, 'entradaGlobal'])->name('inventarios.entrada.global');
    Route::post('/inventarios/entrada-global', [InventarioController::class, 'storeEntradaGlobal'])->name('inventarios.entrada.global.store');
    Route::get('/inventarios/exportar', [InventarioController::class, 'exportarExcel'])->name('inventarios.exportar');
    Route::delete('/inventarios/{id}', [InventarioController::class, 'destroy'])->name('inventarios.destroy');
    Route::delete('/lotes/{id}', [InventarioController::class, 'destroyLote'])->name('lotes.destroy');
    Route::get('/inventarios/{id}/lotes', [InventarioController::class, 'showLotes'])->name('inventarios.lotes');

    // MOVIMIENTOS
    Route::get('/movimientos', [MovimientoController::class, 'index'])->name('movimientos.index');
    Route::delete('/movimientos/{movimiento}', [MovimientoController::class, 'destroy'])->name('movimientos.destroy');

    // PRODUCTOS
    Route::resource('productos', ProductoController::class);
    Route::get('/productos/buscar', [ProductoController::class, 'buscar'])->name('productos.buscar');

    // CLIENTES
    Route::resource('clientes', ClienteController::class);
    Route::get('/clientes/buscar', [ClienteController::class, 'buscar'])->name('clientes.buscar');
    Route::get('/clientes/exportar', [ClienteController::class, 'exportarExcel'])->name('clientes.exportar');

    // CUENTAS
    Route::resource('cuentas', CuentaController::class);
    Route::get('/cuentas/pagadas', [CuentaController::class, 'pagadas'])->name('cuentas.pagadas');
    Route::put('/cuentas/{cuenta}/cerrar', [CuentaController::class, 'cerrar'])->name('cuentas.cerrar');
    Route::post('/cuentas/{cuenta}/marcar-pagada', [CuentaController::class, 'marcarPagada'])->name('cuentas.marcarPagada');

    // RUTA PARA IMPRIMIR EL TICKET DESDE PANEL
    Route::get('/cuentas/{cuenta}/imprimir/{area}', [TicketController::class, 'imprimirTicket'])->name('cuentas.imprimir');
});

// SI QUIERES LAS RUTAS DE AUTH PROPIAS DE LARAVEL, AGRÉGALAS SOLO SI LAS NECESITAS
// require __DIR__.'/auth.php';