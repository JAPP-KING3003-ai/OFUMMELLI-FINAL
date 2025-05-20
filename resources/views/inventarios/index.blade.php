<x-app-layout>

<head>
    <style>
        /* Tooltip popover ultra compatible: */
        .tooltip-pop {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        cursor: pointer;
        white-space: nowrap; /* ¡Importante para nunca partir la línea! */
        }
        .tooltip-pop .tooltip-poptext {
        visibility: hidden;
        min-width: 210px;
        background-color: #fff;
        color: #23272f;
        text-align: left;
        border-radius: 8px;
        border: 1px solid #dadada;
        padding: 12px 16px;
        position: absolute;
        z-index: 100;
        left: 50%;
        transform: translateX(-50%);
        top: 140%;
        box-shadow: 0 4px 24px rgba(0,0,0,0.1);
        font-size: 0.95rem;
        opacity: 0;
        transition: opacity 0.2s;
        /* Dark mode compatibility */
        }
        .dark .tooltip-pop .tooltip-poptext {
        background-color: #232a39;
        color: #e2e8f0;
        border-color: #44495a;
        }
        .tooltip-pop:hover .tooltip-poptext,
        .tooltip-pop:focus .tooltip-poptext {
        visibility: visible;
        opacity: 1;
        }

        form a {
            margin: 3px 3px;
        }

        #boton-eliminar {
            margin-left: 3px;
        }
    </style>
</head>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-900 dark:text-gray-100">
            {{ __('Listado de Inventario') }}
        </h2>
    </x-slot>

    <div class="py-6 bg-gray-100 dark:bg-gray-900">
        <div class="mx-auto max-w-8xl sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if (session('success'))
                    <div class="mb-4 text-green-600">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- BARRA DE BÚSQUEDA Y BOTÓN EXPORTAR -->
                <div class="flex justify-between items-center mb-4">
                    <form method="GET" action="{{ route('inventarios.index') }}" class="flex space-x-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre o código..." class="w-full border rounded px-4 py-2 dark:bg-gray-700 dark:text-white">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Buscar</button>
                    </form>

                    <!-- Botón Nuevo Producto -->
                    <a href="{{ route('inventarios.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Nuevo Producto
                    </a>

                    <a href="{{ route('inventarios.exportar') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        Exportar a Excel
                    </a>
                </div>

                <!-- TABLA DE INVENTARIOS -->
                <div>
                    <table class="min-w-[900px] w-full bg-white dark:bg-gray-800 whitespace-nowrap">
                        @php
                            function nextDir($col) {
                                return (request('sort') === $col && request('direction') === 'asc') ? 'desc' : 'asc';
                            }
                            $sort = request('sort', 'created_at');
                            $dir = request('direction', 'desc');
                        @endphp
                        <thead>
                            <tr class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                <th class="py-2 px-4 border-b dark:border-gray-600">
                                    <a href="{{ route('inventarios.index', array_merge(request()->all(), ['sort' => 'codigo', 'direction' => nextDir('codigo')])) }}">
                                        Código
                                        @if($sort === 'codigo')
                                            @if($dir === 'asc') ▲ @else ▼ @endif
                                        @endif
                                    </a>
                                </th>
                                <th class="py-2 px-4 border-b dark:border-gray-600">
                                    Lotes
                                </th>
                                <th class="py-2 px-4 border-b dark:border-gray-600">
                                    <a href="{{ route('inventarios.index', array_merge(request()->all(), ['sort' => 'nombre', 'direction' => nextDir('nombre')])) }}">
                                        Nombre del Producto
                                        @if($sort === 'nombre')
                                            @if($dir === 'asc') ▲ @else ▼ @endif
                                        @endif
                                    </a>
                                </th>
                                <th class="py-2 px-4 border-b dark:border-gray-600">
                                    Cantidad Inicial
                                </th>
                                <th class="py-2 px-4 border-b dark:border-gray-600">
                                    Cantidad Actual
                                </th>
                                <th class="py-2 px-4 border-b dark:border-gray-600">
                                    Precio Costo
                                </th>
                                <th class="py-2 px-4 border-b dark:border-gray-600">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($productos as $producto)
                                <tr class="hover:bg-gray-300 dark:hover:bg-gray-600 whitespace-nowrap">
                                    <td class="text-gray-900 dark:text-gray-100 text-center py-2 px-4 border-b dark:border-gray-600">{{ $producto->codigo }}</td>
                                    <td class="text-gray-900 dark:text-gray-100 text-center py-2 px-4 border-b dark:border-gray-600">
                                        {{ $producto->lotes->count() }} lote(s)
                                        @if($producto->lotes->last())
                                            <small>Último: {{ $producto->lotes->last()->fecha_ingreso }}</small>
                                        @endif
                                    </td>
                                    <td class="text-gray-900 dark:text-gray-100 text-center py-2 px-4 border-b dark:border-gray-600">{{ $producto->nombre }}</td>
                                    <td class="text-gray-900 dark:text-gray-100 text-center py-2 px-4 border-b dark:border-gray-600">{{ $producto->lotes->sum('cantidad_inicial') }}</td>
                                    <td class="text-gray-900 dark:text-gray-100 text-center py-2 px-4 border-b dark:border-gray-600">{{ $producto->lotes->sum('cantidad_actual') }}</td>
                                    @php
                                        $total_costo = $producto->lotes->sum(function($l) {
                                            return $l->cantidad_inicial * $l->precio_costo;
                                        });
                                        $total_cantidad = $producto->lotes->sum('cantidad_inicial');
                                        $precio_promedio = $total_cantidad > 0 ? $total_costo / $total_cantidad : 0;
                                    @endphp
                                    <td class="text-gray-900 dark:text-gray-100 text-center py-2 px-4 border-b dark:border-gray-600 min-w-[140px]">
                                        <span class="tooltip-pop">
                                            ${{ number_format($precio_promedio,2) }}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="inline h-4 w-4 text-blue-400 dark:text-blue-300 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                                                <line x1="12" y1="8" x2="12" y2="12" stroke="currentColor" stroke-width="2"/>
                                                <circle cx="12" cy="16" r="1" fill="currentColor"/>
                                            </svg>
                                            <span class="tooltip-poptext">
                                                <span class="font-semibold text-blue-700 dark:text-blue-300">Detalle de costo</span><br>
                                                <span class="font-medium">Promedio:</span>
                                                ${{ number_format($precio_promedio,2) }}<br>
                                                <span class="font-medium">Total gastado:</span>
                                                ${{ number_format($total_costo,2) }}<br>
                                                <span class="font-medium">Lotes:</span>
                                                {{ $producto->lotes->count() }}
                                            </span>
                                        </span>
                                    </td>
                                    <td class="text-gray-900 dark:text-gray-100 text-center py-2 px-4 border-b dark:border-gray-600 space-x-2">
                                    <form action="{{ route('inventarios.destroy', $producto->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este producto del inventario?');">
                                        <!-- BOTÓN VER LOTES -->
                                            <a href="{{ route('inventarios.lotes', $producto->id) }}" class="px-3 py-1 bg-teal-700 text-white rounded hover:bg-teal-900">
                                                Ver Lotes
                                            </a>

                                            <!-- BOTÓN NUEVA ENTRADA -->
                                            <a href="{{ route('inventarios.entrada', $producto->id) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                                Entrada
                                            </a>

                                            <!-- BOTÓN NUEVA SALIDA -->
                                            <a href="{{ route('inventarios.salida.form', $producto->id) }}" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                                Salida
                                            </a>

                                            <!-- BOTÓN EDITAR -->
                                            <a href="{{ route('inventarios.edit', $producto->id) }}" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                                Editar
                                            </a>

                                            <!-- BOTÓN ELIMINAR -->
                                                @csrf
                                                @method('DELETE')
                                                <button id="boton-eliminar" type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                                    Eliminar
                                                </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="py-2 px-4 border-b text-center dark:text-white" colspan="7">
                                        No hay productos en inventario.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINACIÓN -->
                <div class="mt-4">
                    {{ $productos->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>