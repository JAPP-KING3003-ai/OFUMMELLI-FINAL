<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-900 dark:text-gray-100">
            {{ __('Detalles del Inventario para ') . $producto->nombre }}
        </h2>
    </x-slot>

    <div class="py-6 bg-gray-100 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if(session('success'))
                    <div class="mb-4 text-green-600">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between space-y-2 md:space-y-0">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Lotes Asociados</h3>
                    <!-- Barra de búsqueda -->
                    <form method="GET" action="{{ route('inventarios.lotes', $producto->id) }}" class="flex space-x-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar lote..." class="border rounded px-3 py-1 dark:bg-gray-700 dark:text-white">
                        <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Buscar</button>
                    </form>
                </div>

                <div class="bg-gray-100 dark:bg-gray-900 p-4 mb-6 rounded shadow flex flex-wrap gap-4" 
                style="     display: flex; 
                            flex-direction: row;
                            align-items: center;
                            justify-content: center;
                            flex-wrap: wrap;
                            align-content: center;">
                    @php
                        // Suma total gastada (todas las compras)
                        $total_gastado = $producto->lotes->sum(function($lote) {
                            return $lote->cantidad_inicial * $lote->precio_costo;
                        });
                        // Cantidad total en entradas
                        $total_cantidad = $producto->lotes->sum('cantidad_inicial');
                        // Precio promedio ponderado
                        $precio_promedio = $total_cantidad > 0 ? $total_gastado / $total_cantidad : 0;
                        // Último lote
                        $ultimo_lote = $producto->lotes->sortByDesc('fecha_ingreso')->first();
                    @endphp

                    <div class="rounded-lg shadow p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700">

                        <div class="flex flex-col gap-2 sm:flex-row sm:gap-6 w-full">
                            <div>
                                <span class="font-semibold text-gray-900 dark:text-gray-200">Total gastado (todas las entradas): </span>
                                <span class="font-bold text-green-700 dark:text-green-400">${{ number_format($total_gastado, 2) }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-900 dark:text-gray-200">Precio promedio ponderado: </span>
                                <span class="font-bold text-blue-700 dark:text-blue-400">${{ number_format($precio_promedio, 2) }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-900 dark:text-gray-200">Último precio de entrada registrado: </span>
                                <span class="font-bold text-yellow-700 dark:text-yellow-300">${{ number_format($ultimo_lote?->precio_costo ?? 0, 2) }}</span>
                            </div>
                        </div>
                        <div class="mt-2 sm:mt-0">
                            <span class="font-semibold text-gray-900 dark:text-gray-200">Cantidad total: </span>
                            <span class="font-bold text-pink-700 dark:text-pink-300">{{ $total_cantidad }}</span>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white dark:bg-gray-800 rounded-lg overflow-hidden">
                        <thead>
                            <tr class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                @php
                                    function sortUrl($col, $sort, $direction, $producto) {
                                        $dir = ($sort === $col && $direction === 'asc') ? 'desc' : 'asc';
                                        return route('inventarios.lotes', array_merge([$producto->id], request()->except('page', 'sort', 'direction'), ['sort' => $col, 'direction' => $dir]));
                                    }
                                    $arrow = fn($col) => request('sort') === $col ? (request('direction') === 'asc' ? '↑' : '↓') : '';
                                @endphp
                                <th class="py-2 px-4 border-b dark:border-gray-600 cursor-pointer">
                                    <a href="{{ sortUrl('cantidad_inicial', $sort, $direction, $producto) }}">
                                        Cantidad Inicial {!! $arrow('cantidad_inicial') !!}
                                    </a>
                                </th>
                                <th class="py-2 px-4 border-b dark:border-gray-600 cursor-pointer">
                                    <a href="{{ sortUrl('cantidad_actual', $sort, $direction, $producto) }}">
                                        Cantidad Actual {!! $arrow('cantidad_actual') !!}
                                    </a>
                                </th>
                                <th class="py-2 px-4 border-b dark:border-gray-600 cursor-pointer">
                                    <a href="{{ sortUrl('precio_costo', $sort, $direction, $producto) }}">
                                        Precio de Costo {!! $arrow('precio_costo') !!}
                                    </a>
                                </th>
                                <th class="py-2 px-4 border-b dark:border-gray-600 cursor-pointer">
                                    <a href="{{ sortUrl('fecha_ingreso', $sort, $direction, $producto) }}">
                                        Fecha y Hora de Ingreso {!! $arrow('fecha_ingreso') !!}
                                    </a>
                                </th>
                                <th class="py-2 px-4 border-b dark:border-gray-600">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($lotes as $lote)
                                <tr class="hover:bg-gray-300 dark:hover:bg-gray-600">
                                    <td class="text-gray-900 dark:text-gray-100 text-center py-2 px-4 border-b dark:border-gray-600">{{ $lote->cantidad_inicial }}</td>
                                    <td class="text-gray-900 dark:text-gray-100 text-center py-2 px-4 border-b dark:border-gray-600">{{ $lote->cantidad_actual }}</td>
                                    <td class="text-gray-900 dark:text-gray-100 text-center py-2 px-4 border-b dark:border-gray-600">${{ number_format($lote->precio_costo, 2) }}</td>
                                    <td class="text-gray-900 dark:text-gray-100 text-center py-2 px-4 border-b dark:border-gray-600">
                                        {{ \Carbon\Carbon::parse($lote->fecha_ingreso)->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td class="text-center py-2 px-4 border-b dark:border-gray-600">
                                        <form action="{{ route('lotes.destroy', $lote->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este lote?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-gray-500 dark:text-gray-400">
                                        No hay lotes registrados para este producto.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="mt-4">
                    {{ $lotes->links() }}
                </div>

                <br>
                
                <div class="mt-4">
                    <a href="{{ route('inventarios.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Volver al Inventario</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>