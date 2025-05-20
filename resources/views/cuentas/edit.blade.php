@extends('layouts.app')

@section('content')

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="max-w-5xl mx-auto py-10 px-6" style="max-width: 80rem;">
    <div class="bg-light-card dark:bg-dark-card shadow rounded-xl p-8">
        <h2 class="text-2xl font-bold text-light-text dark:text-dark-text mb-6">Editar Cuenta #{{ $cuenta->id }}</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-6">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">¡Éxito!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

        <form action="{{ route('cuentas.update', $cuenta->id) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    <!-- Campo para tasa_dia -->
<div class="flex items-center gap-2">
    <label for="tasa_dia" class="block font-medium text-sm text-light-text dark:text-dark-text">
        Tasa de Cambio Actual:
    </label>
    <input
        type="number"
        step="0.01"
        name="tasa_dia"
        id="tasa_dia"
        value="{{ old('tasa_dia', $cuenta->tasa_dia ?? $tasaActual ?? 1) }}"
        class="w-32 rounded-md border border-light-border dark:border-dark-border bg-light-card dark:bg-dark-card text-light-text dark:text-dark-text"
        oninput="calcularRestante()"
    >
</div>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="cliente_nombre" class="block text-sm font-medium text-white mb-1">Nombre Manual</label>
                    <input type="text" name="cliente_nombre" id="cliente_nombre" value="{{ old('cliente_nombre', $cuenta->cliente_nombre) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label for="responsable" class="block text-sm font-medium text-white mb-1">Responsable</label>
                    <input type="text" name="responsable" id="responsable" value="{{ old('responsable', $cuenta->responsable_pedido) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                                    <!-- Barrero -->
                    <div class="form-group">
                        <label for="barrero" class="block text-sm font-medium text-white mb-1">Nombre del Barrero</label>
                        <input type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" name="barrero" id="barrero" class="form-control" value="{{ $cuenta->barrero }}" placeholder="Ingrese el nombre del barrero">
                    </div>

                <div>
                    <label for="estacion" class="block text-sm font-medium text-white mb-1">Estación</label>
                    <input type="text" name="estacion" id="estacion" value="{{ old('estacion', $cuenta->estacion) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label for="fecha_hora" class="block text-sm font-medium text-white mb-1">Fecha y Hora</label>
                    <input type="datetime-local" name="fecha_hora" id="fecha_hora" value="{{ old('fecha_hora', \Carbon\Carbon::parse($cuenta->fecha_apertura)->format('Y-m-d\TH:i')) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

              <!-- Productos -->
            <div>
                <label class="block text-sm font-medium text-white mb-2">Productos</label>
                <div id="productos-container" class="space-y-4">
    @foreach ($productosSeleccionados as $producto)
        <div class="grid grid-cols-12 gap-4 producto-item">
            <div class="col-span-6">
                <select name="productos[]" class="w-full border-gray-300 rounded-md" step="0.01">
                    @foreach ($productos as $item)
                        <option value="{{ $item->id }}" {{ $producto['producto_id'] == $item->id ? 'selected' : '' }}>
                            {{ $item->nombre }}
                        </option>
                    @endforeach
                </select>
                <input type="hidden" name="areas[]" value="{{ $producto['area_id'] ?? 'sin-area' }}"> <!-- Incluir el área -->
            </div>
            <div class="col-span-4">
                <input type="number" name="cantidades[]" value="{{ $producto['cantidad'] }}" min="1" class="w-full border-gray-300 rounded-md">
            </div>

            <!-- BOTON DE IMPRIMIR FUNCIONAL -->

                <!-- <div class="col-span-2 flex items-center space-x-2">
                    <button type="button"
                            class="imprimir-producto w-full bg-blue-500 text-white px-2 py-1 rounded-md hover:bg-blue-600"
                            data-producto-id="{{ $producto['producto_id'] }}"
                            data-area-id="{{ $producto['area_id'] }}"
                            {{ isset($producto['printed_at']) && $producto['printed_at'] ? 'disabled' : '' }}>
                        {{ isset($producto['printed_at']) && $producto['printed_at'] ? 'Ya Impreso' : 'Imprimir' }}
                    </button>
                    <span id="impreso-indicador-{{ $producto['producto_id'] }}" 
                        class="{{ isset($producto['printed_at']) && $producto['printed_at'] ? '' : 'hidden' }} text-green-500 font-bold">
                        Impreso
                    </span>
                </div> -->
                
            <!-- FIN DEL BOTON DE IMPRIMIR FUNCIONAL -->

            <!-- CAMPO DE PRUEBA PARA BOTON DE IMPRIMIR -->

<!-- <div class="col-span-2 flex items-center space-x-2">
    <button type="button"
            class="imprimir-producto w-full bg-blue-500 text-white px-2 py-1 rounded-md hover:bg-blue-600"
            data-producto-id="{{ $producto['producto_id'] }}"
            data-area-id="{{ $producto['area_id'] ?? 'sin-area' }}"
            {{ isset($producto['printed_at']) && $producto['printed_at'] ? 'disabled' : '' }}>
        {{ isset($producto['printed_at']) && $producto['printed_at'] ? 'Ya Impreso' : 'Imprimir' }}
    </button>
    <span id="impreso-indicador-{{ $producto['producto_id'] }}" 
          class="{{ isset($producto['printed_at']) && $producto['printed_at'] ? '' : 'hidden' }} text-green-500 font-bold">
          Impreso
    </span>
</div> -->

<div class="col-span-2 flex items-center space-x-2">
    <button type="button"
            class="imprimir-producto w-full bg-blue-500 text-white px-2 py-1 rounded-md hover:bg-blue-600"
            data-id-unico="{{ $producto['id_unico'] }}"
            data-producto-id="{{ $producto['producto_id'] }}"
            {{ isset($producto['printed_at']) && $producto['printed_at'] ? 'disabled' : '' }}>
        {{ isset($producto['printed_at']) && $producto['printed_at'] ? 'Ya Impreso' : 'Imprimir' }}
    </button>
    <span id="impreso-indicador-{{ $producto['id_unico'] }}" 
          class="{{ isset($producto['printed_at']) && $producto['printed_at'] ? '' : 'hidden' }} text-green-500 font-bold">
          Impreso
    </span>
</div>

            @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">¡Éxito!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Error:</strong>
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif

            <!-- FIN DEL CAMPO DE PRUEBA PARA BOTON DE IMPRIMIR -->

            <div class="col-span-2">
                <button type="button" class="remove-producto w-full bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600">Eliminar</button>
            </div>
        </div>
    @endforeach
</div>

                <button type="button" id="agregar-producto" class="mt-3 inline-block bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm">+ Agregar Producto</button>


            <p class="text-green-600 font-bold  m-4">
                Total Estimado: $<span id="total-estimado">{{ number_format($cuenta->total_estimado, 2) }}</span>
            </p>
            
        <!-- Indicadores de restante -->
        <p class="text-light-text dark:text-dark-text font-bold mb-6">
            Restante por Pagar en Dólares: <span id="restante-por-pagar-dolares" style="color: red;">0.00</span>
        </p>
        <p class="text-light-text dark:text-dark-text font-bold mb-6">
            Restante por Pagar en Bolívares: <span id="restante-por-pagar-bolivares" style="color: red;">0.00</span>
        </p>

        <p class="text-light-danger dark:text-dark-danger font-bold">
            <strong>Vuelto:</strong> $<span id="vuelto">0.00</span>
        </p>


        <br>


<!-- Métodos de Pago -->
<div>
    <label class="block text-sm font-medium text-light-text dark:text-dark-text mb-2">Métodos de Pago</label>
    <div id="metodos-pago-container" class="space-y-4">
        @if (!empty($metodosPago))
            @foreach ($metodosPago as $pago)
                <div class="grid grid-cols-12 gap-4 metodo-pago-item mb-2">
                    <div class="col-span-3">
                        <select name="metodo_pago[]" class="w-full border-gray-300 rounded-md metodo-select">
                            <option value="divisas" {{ $pago['metodo'] == 'divisas' ? 'selected' : '' }}>Divisas ($)</option>
                            <option value="pago_movil" {{ $pago['metodo'] == 'pago_movil' ? 'selected' : '' }}>Pago Móvil</option>
                            <option value="zelle" {{ $pago['metodo'] == 'zelle' ? 'selected' : '' }}>Zelle (Dólares)</option>
                            <option value="punto_venta" {{ $pago['metodo'] == 'punto_venta' ? 'selected' : '' }}>Punto de Venta</option>
                            <option value="tarjeta_credito_dolares" {{ $pago['metodo'] == 'tarjeta_credito_dolares' ? 'selected' : '' }}>Tarjeta de Crédito (Dólares)</option>
                            <option value="tarjeta_credito_bolivares" {{ $pago['metodo'] == 'tarjeta_credito_bolivares' ? 'selected' : '' }}>Tarjeta de Crédito (Bolívares)</option>
                            <option value="bs_efectivo" {{ $pago['metodo'] == 'bs_efectivo' ? 'selected' : '' }}>Bolívares en Efectivo</option>
                            <!-- <option value="debito" {{ $pago['metodo'] == 'debito' ? 'selected' : '' }}>Tarjeta Débito</option> -->
                            <option value="euros" {{ $pago['metodo'] == 'euros' ? 'selected' : '' }}>Euros en Efectivo</option>
                            <option value="cuenta_casa" {{ $pago['metodo'] == 'cuenta_casa' ? 'selected' : '' }}>Cuenta Por la Casa</option>
                            <option value="propina_divisas" {{ $pago['metodo'] == 'propina_divisas' ? 'selected' : '' }}>Propina (Divisas)</option>
                            <option value="propina_bolivares" {{ $pago['metodo'] == 'propina_bolivares' ? 'selected' : '' }}>Propina (Bolívares)</option>
                        </select>
                    </div>

                    <div class="col-span-3">
                        <input type="number" name="monto_pago[]" value="{{ $pago['monto'] }}" placeholder="Monto" class="w-full border-gray-300 rounded-md" step="0.01">
                    </div>

                    <!-- Campo para Referencia -->
                    <div class="col-span-3">
                        <input type="text" name="referencia_pago[]" value="{{ $pago['referencia'] }}" placeholder="Referencia" class="w-full border-gray-300 rounded-md referencia-input">
                    </div>

                    <!-- Campo para Banco (Solo para Punto de Venta) -->
                    <div class="col-span-3 {{ $pago['metodo'] == 'punto_venta' ? '' : 'hidden' }} banco-punto-venta">
                        <select name="banco_punto_venta[]" class="w-full border-gray-300 rounded-md">
                            <option value="venezuela" {{ $pago['banco'] == 'venezuela' ? 'selected' : '' }}>Banco de Venezuela</option>
                            <option value="bancamiga" {{ $pago['banco'] == 'bancamiga' ? 'selected' : '' }}>Bancamiga</option>
                        </select>
                    </div>

                    <!-- Nuevo Campo para Cuentas de Pago Móvil -->
                    <div class="col-span-3 {{ $pago['metodo'] == 'pago_movil' ? '' : 'hidden' }} cuenta-pago-movil">
                        <select name="cuenta_pago_movil[]" class="w-full border-gray-300 rounded-md">
                            <option value="">Seleccionar Cuenta</option>
                            <option value="genesis_venezuela" {{ $pago['cuenta'] == 'genesis_venezuela' ? 'selected' : '' }}>Banco de Venezuela - Genesis</option>
                            <option value="esmerley_venezuela" {{ $pago['cuenta'] == 'esmerley_venezuela' ? 'selected' : '' }}>Banco de Venezuela - Esmerley</option>
                            <option value="genesis_banesco" {{ $pago['cuenta'] == 'genesis_banesco' ? 'selected' : '' }}>Banco Banesco - Genesis</option>
                            <option value="esmerley_banesco" {{ $pago['cuenta'] == 'esmerley_banesco' ? 'selected' : '' }}>Banco Banesco - Esmerley</option>
                        </select>
                    </div>

                    <!-- Campo para Autorizado Por (Cuenta Por la Casa) -->
                    <div class="col-span-3 {{ $pago['metodo'] == 'cuenta_casa' ? '' : 'hidden' }} cuenta-casa-autorizado">
                        <input type="text" name="cuenta_casa_autorizado[]" value="{{ $pago['autorizado_por'] }}" placeholder="Autorizado por" class="w-full border-gray-300 rounded-md">
                    </div>

                    <div class="col-span-2">
                        <button type="button" class="remove-metodo w-full bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600">Eliminar</button>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <button type="button" id="agregar-metodo" class="mt-3 inline-block bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm">+ Agregar Método de Pago</button>
</div>

            <!-- Botón Submit -->
            <div class="text-right">

                <input type="hidden" id="tasa-cambio" value="">


            <!-- Botón Actualizar Cuenta -->
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700">Actualizar Cuenta</button>
                      
            </div>            
        </form> 
    </div>
</div>

<!-- Campos ocultos para enviar total_pagado y vuelto -->
<input type="hidden" name="total_pagado" id="hidden-total-pagado" value="0.00">
<input type="hidden" name="vuelto" id="hidden-vuelto" value="0.00">
<input type="hidden" name="printed_at[]" value="{{ $producto['printed_at'] ?? '' }}">


<!-- JAVASCRIPT 100% FUNCIONAL -->

<script>
    $(document).ready(function () {
        // Inicializar Select2 en todos los productos existentes
        $('.producto-select').select2({
            placeholder: 'Busca un producto...',
            width: '100%'
        });

        // Función para calcular el restante
        function calcularRestante() {
            const tasaCambio = parseFloat($('#tasa_dia').val()) || 0;
            const totalEstimado = parseFloat($('#total-estimado').text()) || 0;
            let totalPagado = 0;

            $('#metodos-pago-container .metodo-pago-item').each(function () {
                const metodo = $(this).find('select[name="metodo_pago[]"]').val();
                const monto = parseFloat($(this).find('input[name="monto_pago[]"]').val()) || 0;

                if (['divisas', 'zelle', 'tarjeta_credito_dolares', 'propina_divisas'].includes(metodo)) {
                    totalPagado += monto; // Pagos en dólares
                } else if (['pago_movil', 'bs_efectivo', 'debito', 'punto_venta', 'tarjeta_credito_bolivares', 'propina_bolivares'].includes(metodo)) {
                    totalPagado += monto / (tasaCambio || 1); // Pagos en bolívares
                }
            });

            const restanteDolares = Math.max(0, totalEstimado - totalPagado);
            const restanteBolivares = restanteDolares * tasaCambio;

            $('#restante-por-pagar-dolares').text(restanteDolares.toFixed(2));
            $('#restante-por-pagar-bolivares').text(restanteBolivares.toFixed(2));

            $('#restante-por-pagar-dolares').css('color', restanteDolares === 0 ? 'green' : 'red');
            $('#restante-por-pagar-bolivares').css('color', restanteBolivares === 0 ? 'green' : 'red');
            
            // Actualizar placeholders dinámicos
            $('#metodos-pago-container .metodo-pago-item').each(function () {
                const metodo = $(this).find('select[name="metodo_pago[]"]').val();
                const inputMonto = $(this).find('input[name="monto_pago[]"]');

                if (['divisas', 'zelle', 'tarjeta_credito_dolares', 'propina_divisas'].includes(metodo)) {
                    inputMonto.attr('placeholder', restanteDolares > 0 ? restanteDolares.toFixed(2) : '0.00');
                } else if (['pago_movil', 'bs_efectivo', 'debito', 'punto_venta', 'tarjeta_credito_bolivares', 'propina_bolivares'].includes(metodo)) {
                    inputMonto.attr('placeholder', restanteBolivares > 0 ? restanteBolivares.toFixed(2) : '0.00');
                } else {
                    inputMonto.attr('placeholder', '0.00'); // Default placeholder
                }
            });
        }

        // Función para mostrar/ocultar campos adicionales según el método de pago seleccionado
        function actualizarCamposMetodoPago() {
            $('#metodos-pago-container .metodo-pago-item').each(function () {
                const metodo = $(this).find('.metodo-select').val();
                const referenciaInput = $(this).find('.referencia-input');
                const cuentaPagoMovil = $(this).find('.cuenta-pago-movil');
                const bancoPuntoVenta = $(this).find('.banco-punto-venta');
                const cuentaCasaAutorizado = $(this).find('.cuenta-casa-autorizado');

                // Configuración dinámica según el método de pago seleccionado
                if (metodo === 'pago_movil') {
                    referenciaInput.removeClass('hidden').show();
                    cuentaPagoMovil.removeClass('hidden').show();
                    bancoPuntoVenta.addClass('hidden').hide();
                    cuentaCasaAutorizado.addClass('hidden').hide();
                } else if (['zelle', 'tarjeta_credito_dolares', 'tarjeta_credito_bolivares'].includes(metodo)) {
                    referenciaInput.removeClass('hidden').show();
                    cuentaPagoMovil.addClass('hidden').hide();
                    bancoPuntoVenta.addClass('hidden').hide();
                    cuentaCasaAutorizado.addClass('hidden').hide();
                } else if (metodo === 'punto_venta') {
                    referenciaInput.removeClass('hidden').show();
                    bancoPuntoVenta.removeClass('hidden').show();
                    cuentaPagoMovil.addClass('hidden').hide();
                    cuentaCasaAutorizado.addClass('hidden').hide();
                } else if (metodo === 'cuenta_casa') {
                    referenciaInput.addClass('hidden').hide().val('');
                    cuentaPagoMovil.addClass('hidden').hide();
                    bancoPuntoVenta.addClass('hidden').hide();
                    cuentaCasaAutorizado.removeClass('hidden').show();
                } else if (['propina_divisas', 'propina_bolivares'].includes(metodo)) {
                    referenciaInput.addClass('hidden').hide().val('');
                    cuentaPagoMovil.addClass('hidden').hide();
                    bancoPuntoVenta.addClass('hidden').hide();
                    cuentaCasaAutorizado.addClass('hidden').hide();
                } else {
                    referenciaInput.addClass('hidden').hide().val('');
                    cuentaPagoMovil.addClass('hidden').hide();
                    bancoPuntoVenta.addClass('hidden').hide();
                    cuentaCasaAutorizado.addClass('hidden').hide();
                }
            });
        }

        // Función para agregar un nuevo producto
        function agregarProducto() {
            const newRow = `
                <div class="grid grid-cols-12 gap-4 producto-item">
                    <div class="col-span-6">
                        <select name="productos[]" class="producto-select w-full border-gray-300 rounded-md">
                            @foreach ($productos as $item)
                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-4">
                        <input type="number" name="cantidades[]" value="1" min="1" class="w-full border-gray-300 rounded-md">
                    </div>
                    <div class="col-span-2">
                        <button type="button" class="remove-producto w-full bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600">Eliminar</button>
                    </div>
                </div>
            `;
            $('#productos-container').append(newRow);
            $('.producto-select').last().select2({
                placeholder: 'Busca un producto...',
                width: '100%'
            });
        }
        

        // Función para agregar un nuevo método de pago dinámicamente
        function agregarMetodoPago() {
            const metodoHTML = `
                <div class="grid grid-cols-12 gap-4 metodo-pago-item mb-2">
                    <div class="col-span-3">
                        <select name="metodo_pago[]" class="w-full border-gray-300 rounded-md metodo-select">
                            <option value="">Seleccionar Método</option>
                            <option value="divisas">Divisas ($)</option>
                            <option value="pago_movil">Pago Móvil</option>
                            <option value="zelle">Zelle</option>
                            <option value="punto_venta">Punto de Venta</option>
                            <option value="tarjeta_credito_dolares">Tarjeta de Crédito (Dólares)</option>
                            <option value="tarjeta_credito_bolivares">Tarjeta de Crédito (Bolívares)</option>
                            <option value="bs_efectivo">Bolívares en Efectivo</option>
                            <option value="euros">Euros en Efectivo</option>
                            <option value="cuenta_casa">Cuenta Por la Casa</option>
                            <option value="propina_divisas">Propina (Divisas)</option>
                            <option value="propina_bolivares">Propina (Bolívares)</option>
                        </select>
                    </div>
                    <div class="col-span-3">
                        <input type="number" name="monto_pago[]" placeholder="Monto" class="w-full border-gray-300 rounded-md" step="0.01">
                    </div>
                    <div class="col-span-3">
                        <input type="text" name="referencia_pago[]" placeholder="Referencia (Opcional)" class="w-full border-gray-300 rounded-md referencia-input hidden">
                    </div>
                    <div class="col-span-3 hidden cuenta-pago-movil">
                        <select name="cuenta_pago_movil[]" class="w-full border-gray-300 rounded-md">
                            <option value="seleccionar_cuenta">Seleccionar Cuenta</option>
                            <option value="genesis_venezuela">Banco de Venezuela - Genesis</option>
                            <option value="esmerley_venezuela">Banco de Venezuela - Esmerley</option>
                            <option value="genesis_banesco">Banco Banesco - Genesis</option>
                            <option value="esmerley_banesco">Banco Banesco - Esmerley</option>
                            <option value="juridica">Cuenta Jurídica</option>
                        </select>
                    </div>
                    <div class="col-span-3 hidden banco-punto-venta">
                        <select name="banco_punto_venta[]" class="w-full border-gray-300 rounded-md">
                            <option value="venezuela">Banco de Venezuela</option>
                            <option value="bancamiga">Bancamiga</option>
                        </select>
                    </div>
                    <div class="col-span-3 hidden cuenta-casa-autorizado">
                        <input type="text" name="cuenta_casa_autorizado[]" placeholder="Autorizado por" class="w-full border-gray-300 rounded-md">
                    </div>
                    <div class="col-span-2">
                        <button type="button" class="remove-metodo w-full bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600">Eliminar</button>
                    </div>
                </div>
            `;
            $('#metodos-pago-container').append(metodoHTML);
        }

        // Eventos para agregar y eliminar productos y métodos de pago
        $('#agregar-producto').on('click', agregarProducto);
        $('#agregar-metodo').on('click', agregarMetodoPago);

        $('#productos-container').on('click', '.remove-producto', function () {
            $(this).closest('.producto-item').remove();
        });

        $('#metodos-pago-container').on('click', '.remove-metodo', function () {
            $(this).closest('.metodo-pago-item').remove();
            calcularRestante();
        });

        // Eventos para actualizar campos en métodos de pago
        $('#metodos-pago-container').on('change', '.metodo-select', actualizarCamposMetodoPago);

        // Recalcular restante en eventos relevantes
        $('#metodos-pago-container').on('input', 'input[name="monto_pago[]"], select[name="metodo_pago[]"]', calcularRestante);
        $('#tasa_dia').on('input', calcularRestante);

        $('#metodos-pago-container').on('keydown', 'input[name="monto_pago[]"]', function (event) {
            if (event.key === 'Tab') {
                const placeholder = $(this).attr('placeholder');
                if (!$(this).val() && placeholder) {
                    $(this).val(placeholder);
                    calcularRestante();
                }
            }
        });

        // Configuración inicial al cargar la página
        actualizarCamposMetodoPago();
        calcularRestante();
    });

</script>

 <!-- FIN DE JAVASCRIPT 100% FUNCIONAL -->

<script>
    $(document).ready(function () {
        // Función para calcular el vuelto correctamente según las monedas
        function calcularVuelto() {
            const totalEstimado = parseFloat($('#total-estimado').text()) || 0;
            const tasaCambio = parseFloat($('#tasa_dia').val()) || 1; // Tasa de cambio para convertir bolívares a dólares
            let totalPagadoEnDolares = 0;

            // Sumar todos los montos ingresados en los métodos de pago
            $('#metodos-pago-container .metodo-pago-item').each(function () {
                const metodo = $(this).find('select[name="metodo_pago[]"]').val();
                const monto = parseFloat($(this).find('input[name="monto_pago[]"]').val()) || 0;

                // Convertir montos a dólares según el método de pago
                if (['divisas', 'zelle', 'tarjeta_credito_dolares'].includes(metodo)) {
                    totalPagadoEnDolares += monto; // Pagos en dólares no requieren conversión
                } else if (['pago_movil', 'bs_efectivo', 'debito', 'punto_venta', 'tarjeta_credito_bolivares'].includes(metodo)) {
                    totalPagadoEnDolares += monto / tasaCambio; // Convertir bolívares a dólares
                } else if (metodo === 'euros') {
                    const tasaEuroDolar = 1.1; // Tasa fija de EUR a USD (ajústala según tus necesidades)
                    totalPagadoEnDolares += monto * tasaEuroDolar; // Convertir euros a dólares
                }
            });

            // Calcular el vuelto
            const vuelto = totalPagadoEnDolares > totalEstimado ? totalPagadoEnDolares - totalEstimado : 0;

            // Actualizar los campos visibles
            $('#total-pagado').text(totalPagadoEnDolares.toFixed(2));
            $('#vuelto').text(vuelto.toFixed(2));

            // Actualizar los campos ocultos
            $('#hidden-total-pagado').val(totalPagadoEnDolares.toFixed(2));
            $('#hidden-vuelto').val(vuelto.toFixed(2));
        }

        // Recalcular el vuelto al modificar los montos de pago o la tasa de cambio
        $(document).on('input', 'input[name="monto_pago[]"], #tasa_dia', calcularVuelto);

        // Ejecutar el cálculo inicial al cargar la página
        calcularVuelto();

        // Asegurarse de que los valores se actualicen antes de enviar el formulario
        $('form').on('submit', function (e) {
            calcularVuelto();
        });
    });
</script>

<!-- ESTE SCRIPT ES EL MÁS FUNCIONAL PARA IMPRIMIR LOS TICKETS -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const imprimirBotones = document.querySelectorAll('.imprimir-producto');

    imprimirBotones.forEach(boton => {
        boton.addEventListener('click', function () {
            const idUnico = this.getAttribute('data-id-unico'); // Identificador único
            const productoId = this.getAttribute('data-producto-id'); // ID del producto
            const cuentaId = "{{ $cuenta->id }}"; // ID de la cuenta actual
            const botonImprimir = this; // Referencia al botón que se hizo clic

            // Realizar la solicitud AJAX para imprimir
           fetch(`/cuentas/${cuentaId}/imprimir-producto/${productoId}/${idUnico}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Actualizar el botón para indicar que ya ha sido impreso
                        botonImprimir.disabled = true;
                        botonImprimir.textContent = 'Ya Impreso';
                        botonImprimir.classList.remove('bg-blue-500', 'hover:bg-blue-600');
                        botonImprimir.classList.add('bg-gray-400', 'cursor-not-allowed');

                        // Mostrar el indicador de "Impreso"
                        const indicador = document.getElementById(`impreso-indicador-${idUnico}`);
                        if (indicador) {
                            indicador.classList.remove('hidden');
                        }
                    } else {
                        alert(`Error al imprimir: ${data.error}`);
                    }
                })
                .catch(error => {
                    console.error('Error al imprimir:', error);
                    alert('Ocurrió un error al intentar imprimir el ticket.');
                });
        });
    });
    });
</script>

<!-- FIN DEL SCRIPT ES EL MÁS FUNCIONAL PARA IMPRIMIR LOS TICKETS -->

@endsection