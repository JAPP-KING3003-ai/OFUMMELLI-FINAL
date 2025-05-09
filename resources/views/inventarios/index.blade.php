<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-900 dark:text-gray-100">
            {{ __('Listado de Inventario') }}
        </h2>
    </x-slot>

    <div class="py-6 bg-gray-100 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <!-- BARRA DE BÚSQUEDA Y BOTÓN EXPORTAR -->
                <div class="flex justify-between items-center mb-4">
                    <form method="GET" action="{{ route('inventarios.index') }}" class="flex space-x-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre o código..." class="w-full border rounded px-4 py-2 dark:bg-gray-700 dark:text-white">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Buscar</button>
                    </form>
                    <a href="{{ route('inventarios.exportar') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        Exportar a Excel
                    </a>
                </div>

                <!-- TABLA DE INVENTARIOS -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white dark:bg-gray-800">
                        <thead>
                            <tr class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                <th class="py-2 px-4 border-b dark:border-gray-600">Código</th>
                                <th class="py-2 px-4 border-b dark:border-gray-600">Nombre del Producto</th>
                                <th class="py-2 px-4 border-b dark:border-gray-600">Cantidad Inicial</th>
                                <th class="py-2 px-4 border-b dark:border-gray-600">Cantidad Actual</th>
                                <th class="py-2 px-4 border-b dark:border-gray-600">Precio Costo</th>
                                <th class="py-2 px-4 border-b dark:border-gray-600">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($inventarios as $inventario)
                                <tr class="hover:bg-gray-300 dark:hover:bg-gray-600">
                                    <td class="text-center py-2 px-4 border-b dark:border-gray-600 text-light-text dark:text-dark-text">{{ $inventario->producto->codigo }}</td>
                                    <td class="text-center py-2 px-4 border-b dark:border-gray-600 text-light-text dark:text-dark-text">{{ $inventario->producto->nombre }}</td>
                                    <td class="text-center py-2 px-4 border-b dark:border-gray-600 text-light-text dark:text-dark-text">{{ $inventario->cantidad_inicial }}</td>
                                    <td class="text-center py-2 px-4 border-b dark:border-gray-600 text-light-text dark:text-dark-text">{{ $inventario->cantidad_actual }}</td>
                                    <td class="text-center py-2 px-4 border-b dark:border-gray-600 text-light-text dark:text-dark-text">${{ number_format($inventario->precio_costo, 2) }}</td>
                                    <td class="text-center py-2 px-4 border-b dark:border-gray-600 flex space-x-2">
                                        <!-- BOTÓN NUEVA ENTRADA -->
                                        <a href="{{ route('inventarios.entrada', $inventario->id) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                            Entrada
                                        </a>

                                        <!-- BOTÓN EDITAR -->
                                        <a href="{{ route('inventarios.edit', $inventario->id) }}" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                            Editar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="py-2 px-4 border-b text-center dark:text-white" colspan="6">
                                        No hay productos en inventario.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINACIÓN -->
                <div class="mt-4">
                    {{ $inventarios->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>