<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-900 dark:text-gray-100">
            {{ __('Detalles del Inventario para ') . $producto->nombre }}
        </h2>
    </x-slot>

    <div class="py-6 bg-gray-100 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Lotes Asociados</h3>
                </div>

                <table class="min-w-full bg-white dark:bg-gray-800 rounded-lg overflow-hidden">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                            <th class="py-2 px-4 border-b dark:border-gray-600">Cantidad Inicial</th>
                            <th class="py-2 px-4 border-b dark:border-gray-600">Cantidad Actual</th>
                            <th class="py-2 px-4 border-b dark:border-gray-600">Precio de Costo</th>
                            <th class="py-2 px-4 border-b dark:border-gray-600">Fecha de Ingreso</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($producto->lotes as $lote)
                            <tr class="hover:bg-gray-300 dark:hover:bg-gray-600">
                                <td class="py-2 px-4 border-b dark:border-gray-600">{{ $lote->cantidad_inicial }}</td>
                                <td class="py-2 px-4 border-b dark:border-gray-600">{{ $lote->cantidad_actual }}</td>
                                <td class="py-2 px-4 border-b dark:border-gray-600">${{ number_format($lote->precio_costo, 2) }}</td>
                                <td class="py-2 px-4 border-b dark:border-gray-600">{{ $lote->fecha_ingreso }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-500 dark:text-gray-400">
                                    No hay lotes registrados para este producto.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    <a href="{{ route('inventarios.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Volver al Inventario</a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>