<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-900 dark:text-gray-100">
            {{ __('Editar Producto de Inventario') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="mx-auto max-w-lg px-4">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 space-y-8">

                @if ($errors->any())
                    <div class="mb-4">
                        <ul class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('inventarios.update', $inventario->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Nombre del Producto</label>
                        <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $inventario->nombre) }}" required
                            class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white shadow-sm">
                    </div>

                    <div>
                        <label for="codigo" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Código</label>
                        <input type="text" id="codigo" name="codigo" value="{{ old('codigo', $inventario->codigo) }}" required
                            class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white shadow-sm">
                    </div>

                    <div>
                        <label for="unidad_medida" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Unidad de Medida</label>
                        <select name="unidad_medida" id="unidad_medida" required onchange="toggleUnidadPersonalizada()"
                            class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white">
                            <option value="">Selecciona una unidad</option>
                            <option value="kg" {{ old('unidad_medida', $inventario->unidad_medida) == 'kg' ? 'selected' : '' }}>Kilos</option>
                            <option value="unidad" {{ old('unidad_medida', $inventario->unidad_medida) == 'unidad' ? 'selected' : '' }}>Unidades</option>
                            <option value="paquete" {{ old('unidad_medida', $inventario->unidad_medida) == 'paquete' ? 'selected' : '' }}>Paquete(s)</option>
                            <option value="caja" {{ old('unidad_medida', $inventario->unidad_medida) == 'caja' ? 'selected' : '' }}>Caja(s)</option>
                            <option value="litro" {{ old('unidad_medida', $inventario->unidad_medida) == 'litro' ? 'selected' : '' }}>Litros</option>
                            <option value="saco" {{ old('unidad_medida', $inventario->unidad_medida) == 'saco' ? 'selected' : '' }}>Saco(s)</option>
                            <option value="docena" {{ old('unidad_medida', $inventario->unidad_medida) == 'docena' ? 'selected' : '' }}>Docena(s)</option>
                            <option value="bulto" {{ old('unidad_medida', $inventario->unidad_medida) == 'bulto' ? 'selected' : '' }}>Bulto(s)</option>
                            <option value="barra" {{ old('unidad_medida', $inventario->unidad_medida) == 'barra' ? 'selected' : '' }}>Barra(s)</option>
                            <option value="personalizada"
                                {{ !in_array(old('unidad_medida', $inventario->unidad_medida), [
                                    'kg','unidad','paquete','caja','litro','saco','docena','bulto','barra'
                                ]) ? 'selected' : '' }}>Otra (escribir)</option>
                        </select>
                        <input type="text" name="unidad_personalizada" id="unidad_personalizada"
                            class="mt-2 w-full border rounded px-4 py-2 dark:bg-gray-700 dark:text-white"
                            style="display: none;"
                            value="{{ old('unidad_personalizada', (!in_array($inventario->unidad_medida, [
                                'kg','unidad','paquete','caja','litro','saco','docena','bulto','barra'
                            ]) ? $inventario->unidad_medida : '')) }}"
                            placeholder="Escribe la unidad de medida preferida">
                    </div>

                    <div>
                        <label for="cantidad_inicial" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                            Cantidad Inicial <span id="unidad-label">(en {{ $inventario->unidad_medida }})</span>
                        </label>
                        <input type="number" name="cantidad_inicial" id="cantidad_inicial"
                            value="{{ old('cantidad_inicial', $inventario->lotes->first() ? $inventario->lotes->first()->cantidad_inicial : 0) }}"
                            min="0" required
                            class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white shadow-sm">
                    </div>

                    <div>
                        <label for="cantidad_actual" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                            Cantidad Actual
                        </label>
                        <input type="number" name="cantidad_actual" id="cantidad_actual"
                            value="{{ old('cantidad_actual', $inventario->lotes->first() ? $inventario->lotes->first()->cantidad_actual : 0) }}"
                            min="0" required
                            class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white shadow-sm">
                    </div>

                    <div>
                        <label for="costo_total" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                            Costo Total del Lote
                        </label>
                        <input type="number" name="costo_total" id="costo_total"
                            value="{{ old('costo_total', $inventario->lotes->first() ? round($inventario->lotes->first()->precio_costo * $inventario->lotes->first()->cantidad_inicial, 2) : 0) }}"
                            step="0.01" min="0" required
                            class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white shadow-sm">
                    </div>

                    <div>
                        <label for="precio_unitario" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                            Costo por <span id="unidad-label-dos">
                                {{ old('unidad_medida', $inventario->unidad_medida) }}
                            </span> (calculado)
                        </label>
                        <input type="text" id="precio_unitario"
                            class="block w-full rounded-md border-gray-200 bg-gray-100 dark:bg-gray-700 dark:text-white"
                            value="0.00" disabled>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-semibold shadow">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleUnidadPersonalizada() {
            let select = document.getElementById('unidad_medida');
            let input = document.getElementById('unidad_personalizada');
            let unidadLabel = document.getElementById('unidad-label');
            let unidadLabelDos = document.getElementById('unidad-label-dos');
            let selectedOption = select.options[select.selectedIndex];
            if(select.value === 'personalizada') {
                input.style.display = 'block';
                input.required = true;
                unidadLabel.textContent = '(en ' + (input.value || '...') + ')';
                unidadLabelDos.textContent = input.value || 'unidad';
            } else {
                input.style.display = 'none';
                input.required = false;
                unidadLabel.textContent = '(en ' + selectedOption.text + ')';
                unidadLabelDos.textContent = selectedOption.text;
            }
        }

        function calcularPrecioUnitario() {
            const cantidad = parseFloat(document.getElementById('cantidad_inicial').value) || 0;
            const costo_total = parseFloat(document.getElementById('costo_total').value) || 0;
            const precio = (cantidad > 0) ? (costo_total / cantidad) : 0;
            document.getElementById('precio_unitario').value = precio.toFixed(2);
        }

        document.getElementById('unidad_medida').addEventListener('change', toggleUnidadPersonalizada);
        document.getElementById('cantidad_inicial').addEventListener('input', calcularPrecioUnitario);
        document.getElementById('costo_total').addEventListener('input', calcularPrecioUnitario);

        // Soporte para cambio en input cuando es personalizada
        if(document.getElementById('unidad_personalizada')) {
            document.getElementById('unidad_personalizada').addEventListener('input', function() {
                let unidadLabel = document.getElementById('unidad-label');
                let unidadLabelDos = document.getElementById('unidad-label-dos');
                unidadLabel.textContent = '(en ' + (this.value || '...') + ')';
                unidadLabelDos.textContent = this.value || 'unidad';
            });
        }

        // Init
        document.addEventListener('DOMContentLoaded', function() {
            toggleUnidadPersonalizada();
            calcularPrecioUnitario();
        });
    </script>
</x-app-layout>