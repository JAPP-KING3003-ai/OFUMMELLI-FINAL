<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
            {{ __('Listado de Cuentas') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="mb-4 text-green-500 font-semibold">
                            {{ session('success') }}
                        </div>
                    @endif

                <div class="flex items-center justify-between mb-4"> 

                    <form method="GET" action="{{ route('cuentas.index') }}" class="mb-4 flex items-center space-x-2">
                        <div class="relative w-full max-w-sm">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <!-- Lupa (Heroicons) -->
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z"/>
                                </svg>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Buscar cuenta por cliente, responsable o estación..."
                                class="pl-10 pr-4 py-2 border rounded-md w-full text-gray-900"
                            >
                        </div>

                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Buscar
                        </button>

                        @if(request('search'))
                            <a href="{{ route('cuentas.index') }}"
                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                Limpiar
                            </a>
                        @endif
                    </form>

                    {{-- Botón para crear nueva cuenta --}}
                        <a href="{{ route('cuentas.create') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                            + Nueva Cuenta
                        </a>

                        @if (Auth::user()->role === 'Admin')
                            {{-- Botón para ver Cuentas Pagadas --}}
                            <a href="{{ route('cuentas.pagadas') }}" class="bg-indigo-600 text-white px-4 py-2 rounded  hover:bg-indigo-700">
                                Ver Cuentas Pagadas
                            </a>
                        @endif
                    </div>
                </div>


                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        @php
    // Función para cambiar la dirección de ordenamiento
    function sort_link($column, $label, $sort, $direction) {
        $isCurrent = $sort === $column;
        $nextDirection = ($isCurrent && $direction === 'asc') ? 'desc' : 'asc';
        $arrow = $isCurrent ? ($direction === 'asc' ? '↑' : '↓') : '';
        $params = array_merge(request()->except('page'), ['sort' => $column, 'direction' => $nextDirection]);
        $url = url()->current() . '?' . http_build_query($params);
        return '<a href="' . $url . '" class="hover:underline">' . $label . ' ' . $arrow . '</a>';
    }
@endphp
<thead class="bg-gray-700 text-white">
    <tr>
        <th class="px-4 py-2">{!! sort_link('id', 'ID', $sort ?? '', $direction ?? '') !!}</th>
        <th class="px-4 py-2">{!! sort_link('cliente_nombre', 'Cliente', $sort ?? '', $direction ?? '') !!}</th>
        <th class="px-4 py-2">{!! sort_link('responsable_pedido', 'Responsable', $sort ?? '', $direction ?? '') !!}</th>
        <th class="px-4 py-2">{!! sort_link('estacion', 'Estación', $sort ?? '', $direction ?? '') !!}</th>
        <th class="px-4 py-2">{!! sort_link('total_estimado', 'Total', $sort ?? '', $direction ?? '') !!}</th>
        <th class="px-4 py-2">{!! sort_link('fecha_apertura', 'Fecha', $sort ?? '', $direction ?? '') !!}</th>
        <th class="px-4 py-2">Acciones</th>
    </tr>
</thead>
                        <tbody class="bg-white dark:bg-gray-800 text-light-text dark:text-dark-text divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($cuentas as $cuenta)
                                <tr class="hover:bg-gray-300 dark:hover:bg-gray-700">
                                    <td class="px-4 py-2">{{ $cuenta->id }}</td>
                                    <td class="px-4 py-2">
                                        {{ $cuenta->cliente->nombre ?? $cuenta->cliente_nombre ?? 'Sin cliente' }}
                                    </td>
                                    <td class="px-4 py-2">{{ $cuenta->responsable_pedido ?? '—' }}</td>
                                    <td class="px-4 py-2">{{ $cuenta->estacion }}</td>
                                    <td class="px-4 py-2 text-green-700">${{ number_format($cuenta->total_estimado, 2) }}</td>
                                    <td class="px-4 py-2">{{ $cuenta->fecha_apertura }}</td>
                                    <td class="px-4 py-2 space-x-1 flex items-center">
                                        @php
                                            $metodosPago = json_decode($cuenta->metodos_pago, true) ?? [];
                                            $hayMetodos = count($metodosPago) > 0;
                                            $totalSinPropina = 0;
                                            $propinas = 0;
                                            foreach ($metodosPago as $pago) {
                                                $monto = $pago['monto'] ?? 0;
                                                if (in_array($pago['metodo'], ['divisas', 'zelle', 'tarjeta_credito_dolares'])) {
                                                    $totalSinPropina += $monto;
                                                } elseif (in_array($pago['metodo'], ['pago_movil', 'bs_efectivo', 'debito', 'punto_venta', 'tarjeta_credito_bolivares'])) {
                                                    $totalSinPropina += $monto / ($cuenta->tasa_dia ?: 1);
                                                } elseif ($pago['metodo'] === 'euros') {
                                                    $totalSinPropina += $monto * 1.1;
                                                }
                                                if ($pago['metodo'] === 'propina_divisas') {
                                                    $propinas += $monto;
                                                } elseif ($pago['metodo'] === 'propina_bolivares') {
                                                    $propinas += $monto / ($cuenta->tasa_dia ?: 1);
                                                }
                                            }
                                            $totalSinPropina -= $propinas;
                                            $puedePagar = $hayMetodos && $totalSinPropina >= $cuenta->total_estimado;
                                            $motivoTooltip = !$hayMetodos
                                                ? 'No se han agregado métodos de pago'
                                                : (($totalSinPropina < $cuenta->total_estimado) ? 'El pago es insuficiente para pagar esta cuenta' : '');
                                        @endphp

                                        {{-- BOTÓN PAGAR CUENTA CON TOOLTIP --}}
                                        <div class="relative group inline-block">
                                            <a href="{{ route('cuentas.show', $cuenta) }}"
                                            class="inline-block px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm 
                                            {{ !$puedePagar ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}"
                                            {{ !$puedePagar ? 'tabindex="-1" aria-disabled="true"' : '' }}>
                                                Pagar Cuenta
                                            </a>
                                            @if (!$puedePagar)
                                                <div class="absolute z-10 hidden group-hover:block left-1/2 -translate-x-1/2 bottom-full mb-2 w-48 px-2 py-1 bg-gray-800 text-xs text-white rounded shadow-lg text-center">
                                                    {{ $motivoTooltip }}
                                                </div>
                                            @endif
                                        </div>

                                        <a href="{{ route('cuentas.edit', $cuenta) }}"
                                        class="inline-block px-2 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded text-sm">
                                            Editar
                                        </a>

                                        @if (Auth::user()->role === 'Admin')
                                            <form action="{{ route('cuentas.destroy', $cuenta) }}" method="POST" class="inline-block"
                                                onsubmit="return confirm('¿Estás seguro de eliminar esta cuenta?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="px-2 py-1 bg-red-600 hover:bg-red-700 text-white rounded text-sm">
                                                    Eliminar
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Paginación -->
                    <div class="mt-4">
                        {{ $cuentas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>