<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-900 dark:text-gray-100">
            {{ __('Registrar Entrada al Inventario') }}
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="mx-auto max-w-lg px-4">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 space-y-8">

                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-6 border-b pb-2">
                    Producto: <span class="text-primary-600 dark:text-primary-400">{{ $inventario->nombre }}</span>
                </h3>

                @if ($errors->any())
                    <div class="mb-4">
                        <ul class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('inventarios.storeEntrada', $inventario->id) }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="cantidad" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Cantidad a Ingresar</label>
                        <input type="number" name="cantidad" id="cantidad" min="1" required
                               class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                               value="{{ old('cantidad') }}">
                    </div>

                    <div>
                        <label for="costo_total" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Costo Total del Lote</label>
                        <input type="number" name="costo_total" id="costo_total" min="0" step="0.01" required
                               class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                               value="{{ old('costo_total') }}">
                    </div>

                    <div>
                        <label for="precio_unitario" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Precio Costo por Unidad (calculado)</label>
                        <input type="text" id="precio_unitario"
                               class="block w-full rounded-md border-gray-200 bg-gray-100 dark:bg-gray-700 dark:text-white"
                               value="0.00" disabled>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-semibold shadow">
                            Registrar Entrada
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        function calcularPrecioUnitario() {
            const cantidad = parseFloat(document.getElementById('cantidad').value) || 0;
            const costo_total = parseFloat(document.getElementById('costo_total').value) || 0;
            const precio = (cantidad > 0) ? (costo_total / cantidad) : 0;
            document.getElementById('precio_unitario').value = precio.toFixed(2);
        }
        document.getElementById('cantidad').addEventListener('input', calcularPrecioUnitario);
        document.getElementById('costo_total').addEventListener('input', calcularPrecioUnitario);
    </script>
</x-app-layout>