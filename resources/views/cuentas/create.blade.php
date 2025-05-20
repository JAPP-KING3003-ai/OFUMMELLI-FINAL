<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-light-text dark:text-dark-text">
            {{ __('Registrar Nueva Cuenta') }}
        </h2>
    </x-slot>

    <div class="py-6 bg-light-background dark:bg-dark-background">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('cuentas.store') }}" id="form-cuenta">
                @csrf
                <div class="bg-light-card dark:bg-dark-card p-6 rounded shadow border border-light-border dark:border-dark-border">

                    <!-- Cliente (Nombre libre opcional) -->
                    <div class="mb-4">
                        <label for="cliente_nombre" class="block font-medium text-sm text-light-text dark:text-dark-text">
                            Nombre del Cliente (opcional)
                        </label>
                        <input
                            type="text"
                            name="cliente_nombre"
                            id="cliente_nombre"
                            value="{{ old('cliente_nombre') }}"
                            class="w-full rounded-md border border-light-border dark:border-dark-border bg-light-card dark:bg-dark-card text-light-text dark:text-dark-text"
                            placeholder="Ingrese Nombre y Apellido del Cliente">
                        
                        <a href="{{ route('clientes.create') }}"
                        class="inline-block mt-2 px-3 py-1 bg-light-primary dark:bg-dark-primary text-white text-sm rounded hover:bg-light-hover dark:hover:bg-dark-hover">
                        + Registrar nuevo cliente
                        </a>

                        @error('cliente_nombre')
                            <p class="text-danger text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Barrero -->
                    <div class="form-group">
                        <label for="barrero" class="block font-medium text-sm text-light-text dark:text-dark-text">Nombre del Barrero</label>
                        <input type="text" class="w-full rounded-md border border-light-border dark:border-dark-border bg-light-card dark:bg-dark-card text-light-text dark:text-dark-text" name="barrero" id="barrero" class="form-control" placeholder="Nombre del barrero">
                    </div>

                    <br>

                    <!-- Responsable -->
                    <div class="mb-4">
                        <label for="responsable" class="block font-medium text-sm text-light-text dark:text-dark-text">Responsable del Pedido</label>
                        <input type="text" name="responsable" id="responsable" class="w-full rounded-md border border-light-border dark:border-dark-border bg-light-card dark:bg-dark-card text-light-text dark:text-dark-text" placeholder="Ingrese Nombre y Apellido del Responsable">
                    </div>

                    <!-- Estación -->
                    <div class="mb-4">
                        <label for="estacion" class="block font-medium text-sm text-light-text dark:text-dark-text">Estación</label>
                        <select name="estacion" id="estacion" class="select2 w-full border border-light-border dark:border-dark-border bg-light-card dark:bg-dark-card text-light-text dark:text-dark-text">
                            <!-- <option value="Barra">Barra</option>
                            <option value="Cocina">Cocina</option>
                            <option value="Carne en Vara">Carne en Vara</option>
                            <option value="Cachapas">Cachapas</option> -->
                            <option value="Seleccionar Estación">Seleccionar Estación</option>
                            <option value="Churuata">Churuata</option>
                            <option value="Salón Principal">Salón Principal</option>
                        </select>
                    </div>

                    <!-- Fecha -->
                    <div class="mb-4">
                        <label for="fecha_hora" class="block font-medium text-sm text-light-text dark:text-dark-text">Fecha y Hora</label>
                        <input type="datetime-local" name="fecha_hora" id="fecha_hora" value="{{ now()->format('Y-m-d\TH:i') }}" class="w-full rounded-md border border-light-border dark:border-dark-border bg-light-card dark:bg-dark-card text-light-text dark:text-dark-text">
                    </div>

                    <!-- Pedido -->
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-light-text dark:text-dark-text">Pedido</label>
                        <div class="flex gap-2 mb-2">
                            <select id="producto_select" class="select2 w-full border border-light-border dark:border-dark-border bg-light-card dark:bg-dark-card text-light-text dark:text-dark-text">
                                <option value="">-- Buscar producto --</option>
                                @foreach ($productos as $producto)
                                    <option value="{{ $producto->id }}">
                                        {{ $producto->nombre }} - ${{ number_format($producto->precio_venta, 2) }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="number" id="cantidad_input" class="w-24 rounded-md border border-light-border dark:border-dark-border bg-light-card dark:bg-dark-card text-light-text dark:text-dark-text" value="1" min="1">
                            <button type="button" onclick="agregarProducto()" class="bg-light-primary dark:bg-dark-primary text-white px-4 py-2 rounded hover:bg-light-hover dark:hover:bg-dark-hover">Añadir</button>
                        </div>
                        <table class="min-w-full border mt-2 border-light-border dark:border-dark-border" id="tabla-productos">
                            <thead class="bg-light-card dark:bg-dark-card text-light-text dark:text-dark-text">
                                <tr>
                                    <th class="px-4 py-2 border">Producto</th>
                                    <th class="px-4 py-2 border">Cantidad</th>
                                    <th class="px-4 py-2 border">Precio Unitario</th>
                                    <th class="px-4 py-2 border">Subtotal</th>
                                    <th class="px-4 py-2 border">Eliminar</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="px-4 py-2 text-right font-semibold text-light-text dark:text-dark-text">Total Estimado:</td>
                                    <td colspan="2" class="px-4 py-2 font-bold text-light-success dark:text-dark-success" id="total">0.00 $</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Métodos de Pago -->
                        <div class="mb-4" style="display: none;">
                            <label class="block font-medium text-sm text-light-text dark:text-dark-text">Método(s) de Pago</label>
                            <div id="metodos_pago">
                                <!-- Inicialmente vacío -->
                            </div>
                            <button type="button" onclick="agregarMetodoPago()" class="mt-2 bg-light-primary dark:bg-dark-primary text-white px-4 py-2 rounded hover:bg-light-hover dark:hover:bg-dark-hover">+ Agregar Método</button>
                        </div>

                    <!-- Botón -->
                        <div class="mt-6 text-right">
                            <button type="submit" class="bg-light-success dark:bg-dark-success text-white px-6 py-2 rounded hover:bg-light-hover dark:hover:bg-dark-hover">Registrar Cuenta</button>
                        </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Librerías -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- JSON de productos -->
    <script id="productos-json" type="application/json">
        {!! json_encode($productos->mapWithKeys(function ($producto) {
            return [$producto->id => [
                'nombre' => $producto->nombre,
                'precio' => floatval($producto->precio_venta),
                'area_id' => $producto->area_id, // Incluir el área del producto
            ]];
        })) !!}
    </script>

    <script>
        const productosData = JSON.parse(document.getElementById('productos-json').textContent);
        let totalEstimado = 0;

        $(document).ready(function () {
    $('.select2').select2();

    $('#cliente_id').select2({
        placeholder: '-- Buscar cliente --',
        minimumInputLength: 1,
        ajax: {
            url: '{{ route("clientes.buscar") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    term: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
});

        // ✅ Previene recarga al presionar Enter en cantidad
        $(document).on('keydown', function(e) {
            if (e.key === 'Enter') {
                const target = e.target;
                if (target.id === 'cantidad_input') {
                    e.preventDefault();
                    agregarProducto();
                }
            }
        });

        function agregarProducto() {
        const productoId = $('#producto_select').val();
        const cantidad = parseInt($('#cantidad_input').val());

        if (!productoId || cantidad <= 0) return;

        const producto = productosData[productoId];
        if (!producto) return;

        const precio = parseFloat(producto.precio);
        const subtotal = (precio * cantidad).toFixed(2);
        const areaId = producto.area_id; // Obtener el area_id del producto

        totalEstimado += parseFloat(subtotal);

        $('#tabla-productos tbody').append(`
            <tr>
                <td class="px-4 py-2 border text-light-text dark:text-dark-text">
                    <input type="hidden" name="productos[]" value="${productoId}">
                    <input type="hidden" name="areas[]" value="${areaId}"> <!-- Agregar area_id -->
                    ${producto.nombre}
                </td>
                <td class="px-4 py-2 border text-light-text dark:text-dark-text">
                    <input type="number" name="cantidades[]" value="${cantidad}" class="w-16 border rounded bg-light-card dark:bg-dark-card text-light-text dark:text-dark-text">
                </td>
                <td class="px-4 py-2 border text-light-text dark:text-dark-text">$${precio.toFixed(2)}</td>
                <td class="px-4 py-2 border text-light-text dark:text-dark-text">$${subtotal}</td>
                <td class="px-4 py-2 border text-center">
                    <button type="button" class="bg-light-danger dark:bg-dark-danger text-white px-2 py-1 rounded" onclick="eliminarFila(this, ${subtotal})">Eliminar</button>
                </td>
            </tr>
        `);

        $('#total').text(`${totalEstimado.toFixed(2)} $`);
        $('#producto_select').val(null).trigger('change');
        $('#cantidad_input').val(1);
    }

        function eliminarFila(btn, subtotal) {
            $(btn).closest('tr').remove();
            totalEstimado -= parseFloat(subtotal);
            $('#total').text(`${totalEstimado.toFixed(2)} $`);
        }

        function agregarMetodoPago() {
            const metodoHtml = `
                <div class="flex gap-2 mt-2 metodo-pago items-center">
                    <select name="metodo_pago[]" class="metodo-select rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                        <option value="">Seleccionar Método</option>
                        <option value="divisas">Divisas ($)</option>
                        <option value="pago_movil">Pago Móvil</option>
                        <option value="bs_efectivo">Bolívares en Efectivo</option>
                        <option value="debito">Tarjeta Débito</option>
                        <option value="euros">Euros en Efectivo</option>
                        <option value="cuenta_casa">Cuenta Por la Casa</option>
                        <option value="propina">Propina</option>
                    </select>
                    <input type="number" name="monto_pago[]" step="0.01" placeholder="Monto" class="w-28 rounded-md border-gray-300 dark:bg-gray-700 dark:text-white">
                    <input type="text" name="referencia_pago[]" placeholder="Referencia" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white referencia" style="display:none;">
                    <button type="button" class="bg-red-600 text-white px-2 py-1 rounded" onclick="$(this).parent().remove()">Eliminar</button>
                </div>
            `;
            $('#metodos_pago').append(metodoHtml);

            $('.metodo-select').last().on('change', function () {
                const referenciaInput = $(this).siblings('.referencia');
                referenciaInput.toggle($(this).val() === 'pago_movil');
            });
        }

        $(document).ready(function () {
            $('.select2').select2();

            // Configurar evento onchange para el método de pago predeterminado
            $('.metodo-select').on('change', function () {
                const referenciaInput = $(this).siblings('.referencia');
                referenciaInput.toggle($(this).val() === 'pago_movil');
            });
        });

    </script>
</x-app-layout>