@extends('layouts.app')

@section('content')

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="max-w-5xl mx-auto py-10 px-6">
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

            <!-- <form method="POST" action="{{ route('tasa.actualizar') }}" class="mb-4">
    @csrf
    <div class="flex items-center gap-2">
        <label for="tasa_dia" class="block font-medium text-sm text-light-text dark:text-dark-text">
            Tasa de Cambio Actual:
        </label>
        <input
            type="number"
            step="0.01"
            name="tasa_dia"
            id="tasa_dia"
            value="{{ old('tasa_dia', $tasaActual ?? 0) }}"
            class="w-32 rounded-md border border-light-border dark:border-dark-border bg-light-card dark:bg-dark-card text-light-text dark:text-dark-text"
        >
        <button type="submit" class="bg-light-primary dark:bg-dark-primary text-white px-4 py-2 rounded hover:bg-light-hover dark:hover:bg-dark-hover">
            Actualizar Tasa
        </button>
    </div>
</form> -->

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
                        <div class="grid grid-cols-12 gap-4 producto-item "> 
                            <div class="col-span-6">
                                <select name="productos[]" class="producto-select w-full border-gray-300 rounded-md">
                                    @foreach ($productos as $item)
                                        <option value="{{ $item->id }}" {{ $producto['producto_id'] == $item->id ? 'selected' : '' }}>{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-4">
                                <input type="number" name="cantidades[]" value="{{ $producto['cantidad'] }}" min="1" class="w-full border-gray-300 rounded-md">
                            </div>
                            <div class="col-span-2">
                                <button type="button" class="remove-producto w-full bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600">Eliminar</button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="agregar-producto" class="mt-3 inline-block bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-sm">+ Agregar Producto</button>
            </div>

            <p class="text-green-600 font-bold  mb-6">
                Total Estimado: $<span id="total-estimado">{{ number_format($cuenta->total_estimado, 2) }}</span>
            </p>
            
<!-- Indicadores de restante -->
<p class="text-light-text dark:text-dark-text font-bold mb-6">
    Restante por Pagar en Dólares: <span id="restante-por-pagar-dolares" style="color: red;">0.00</span>
</p>
<p class="text-light-text dark:text-dark-text font-bold mb-6">
    Restante por Pagar en Bolívares: <span id="restante-por-pagar-bolivares" style="color: red;">0.00</span>
</p>



            <!-- Métodos de Pago -->
            <div>
                <label class="block text-sm font-medium text-light-text dark:text-dark-text mb-2">Métodos de Pago</label>
                <div id="metodos-pago-container" class="space-y-4">
                    @foreach ($metodosPago as $pago)
                        <div class="grid grid-cols-12 gap-4 metodo-pago-item mb-2">
                            <div class="col-span-4">
                                <select name="metodo_pago[]" class="w-full border-gray-300 rounded-md metodo-select">
                                    <option value="">Seleccionar Método</option>
                                    <option value="divisas" {{ $pago['metodo'] == 'divisas' ? 'selected' : '' }}>Divisas ($)</option>
                                    <option value="pago_movil" {{ $pago['metodo'] == 'pago_movil' ? 'selected' : '' }}>Pago Móvil</option>
                                    <option value="bs_efectivo" {{ $pago['metodo'] == 'bs_efectivo' ? 'selected' : '' }}>Bolívares en Efectivo</option>
                                    <option value="debito" {{ $pago['metodo'] == 'debito' ? 'selected' : '' }}>Tarjeta Débito</option>
                                    <option value="euros" {{ $pago['metodo'] == 'euros' ? 'selected' : '' }}>Euros en Efectivo</option>
                                    <option value="cuenta_casa" {{ $pago['metodo'] == 'cuenta_casa' ? 'selected' : '' }}>Cuenta Por la Casa</option>
                                </select>
                            </div>
                            <div class="col-span-3">
                                <input type="number" name="monto_pago[]" value="{{ $pago['monto'] }}" placeholder="Monto" class="w-full border-gray-300 rounded-md" min="0" step="0.01" required>
                            </div>
                            <div class="col-span-3">
                                <input type="text" name="referencia_pago[]" value="{{ $pago['referencia'] }}" placeholder="Referencia"
                                    class="w-full border-gray-300 rounded-md referencia-input {{ $pago['metodo'] == 'pago_movil' ? '' : 'hidden' }}">
                            </div>
                            <div class="col-span-2">
                                <button type="button" class="remove-metodo w-full bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600">Eliminar</button>
                            </div>
                        </div>
                    @endforeach
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

<!-- JAVASCRIPT 100% FUNCIONAL -->

<script>
    $(document).ready(function () {
        // Inicializar Select2 en todos los productos existentes
        $('.producto-select').select2({
            placeholder: 'Busca un producto...',
            width: '100%'
        });

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

        // Función para agregar un nuevo método de pago
        function agregarMetodoPago() {
            const metodoHTML = `
                <div class="grid grid-cols-12 gap-4 metodo-pago-item mb-2">
                    <div class="col-span-4">
                        <select name="metodo_pago[]" class="w-full border-gray-300 rounded-md metodo-select">
                            <option value="">Seleccionar Método</option>
                            <option value="divisas">Divisas ($)</option>
                            <option value="pago_movil">Pago Móvil</option>
                            <option value="bs_efectivo">Bolívares en Efectivo</option>
                            <option value="debito">Tarjeta Débito</option>
                            <option value="euros">Euros en Efectivo</option>
                            <option value="cuenta_casa">Cuenta Por la Casa</option>
                        </select>
                    </div>
                    <div class="col-span-3">
                        <input type="number" name="monto_pago[]" placeholder="Monto" class="w-full border-gray-300 rounded-md">
                    </div>
                    <div class="col-span-3">
                        <input type="text" name="referencia_pago[]" placeholder="Referencia" class="w-full border-gray-300 rounded-md referencia-input hidden">
                    </div>
                    <div class="col-span-2">
                        <button type="button" class="remove-metodo w-full bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600">Eliminar</button>
                    </div>
                </div>
            `;
            $('#metodos-pago-container').append(metodoHTML);
        }

        // Asignar eventos "click" a los botones
        $('#agregar-producto').off('click').on('click', function () {
            agregarProducto();
        });

        $('#agregar-metodo').off('click').on('click', function () {
            agregarMetodoPago();
        });

        // Eliminar producto
        $('#productos-container').on('click', '.remove-producto', function () {
            $(this).closest('.producto-item').remove();
        });

        // Eliminar método de pago
        $('#metodos-pago-container').on('click', '.remove-metodo', function () {
            $(this).closest('.metodo-pago-item').remove();
        });

        // Mostrar/ocultar campo referencia
        $('#metodos-pago-container').on('change', '.metodo-select', function () {
            const referenciaInput = $(this).closest('.metodo-pago-item').find('.referencia-input');
            if ($(this).val() === 'pago_movil') {
                referenciaInput.removeClass('hidden').show();
            } else {
                referenciaInput.addClass('hidden').hide().val('');
            }
        });

        // Lógica para calcular restante
        function calcularRestante() {
            const tasaCambio = parseFloat($('#tasa_dia').val()) || 0;
            const totalEstimado = parseFloat($('#total-estimado').text()) || 0;
            let totalPagado = 0;

            $('#metodos-pago-container .metodo-pago-item').each(function () {
                const metodo = $(this).find('select[name="metodo_pago[]"]').val();
                const monto = parseFloat($(this).find('input[name="monto_pago[]"]').val()) || 0;

                if (metodo === 'divisas') {
                    totalPagado += monto;
                } else if (['pago_movil', 'bs_efectivo', 'debito'].includes(metodo)) {
                    totalPagado += monto / (tasaCambio || 1);
                }
            });

            const restanteDolares = Math.max(0, totalEstimado - totalPagado);
            const restanteBolivares = restanteDolares * tasaCambio;

            $('#restante-por-pagar-dolares').text(restanteDolares.toFixed(2));
            $('#restante-por-pagar-bolivares').text(restanteBolivares.toFixed(2));

            $('#restante-por-pagar-dolares').css('color', restanteDolares === 0 ? 'green' : 'red');
            $('#restante-por-pagar-bolivares').css('color', restanteBolivares === 0 ? 'green' : 'red');

            $('#metodos-pago-container .metodo-pago-item').each(function () {
                const metodo = $(this).find('select[name="metodo_pago[]"]').val();
                const inputMonto = $(this).find('input[name="monto_pago[]"]');
                if (['pago_movil', 'bs_efectivo', 'debito'].includes(metodo)) {
                    inputMonto.attr('placeholder', restanteBolivares > 0 ? restanteBolivares.toFixed(2) : '0.00');
                }
            });
        }

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

        // Recalcular Total Estimado y Restante al agregar un nuevo producto
$('#agregar-producto').off('click').on('click', function () {
    const newRow = `
        <div class="grid grid-cols-12 gap-4 producto-item">
            <div class="col-span-6">
                <select name="productos[]" class="producto-select w-full border-gray-300 rounded-md">
                    @foreach ($productos as $item)
                        <option value="{{ $item->id }}" data-precio="{{ $item->precio_venta }}">{{ $item->nombre }}</option>
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

    // Reinicializar Select2 para el nuevo producto
    $('.producto-select').last().select2({
        placeholder: 'Busca un producto...',
        width: '100%'
    });

    // Recalcular el total estimado y el restante
    calcularTotalEstimado();
    calcularRestante();
});

        calcularRestante();
    });
</script>

 <!-- FIN DE JAVASCRIPT 100% FUNCIONAL -->

@endsection