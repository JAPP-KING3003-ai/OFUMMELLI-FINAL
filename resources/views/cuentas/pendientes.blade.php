@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Cuentas Pendientes Por Pagar</h2>
        <a href="{{ route('cuentas.create') }}" class="btn btn-success">Nueva Cuenta</a>
    </div>
    <div class="card shadow">
        <div class="card-body">
            @if($cuentas->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Responsable</th>
                            <th>Monto</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cuentas as $cuenta)
                        <tr>
                            <td>{{ $cuenta->id }}</td>
                            <td>{{ $cuenta->cliente_nombre ?? $cuenta->cliente->nombre ?? '-' }}</td>
                            <td>{{ $cuenta->responsable_pedido }}</td>
                            <td><span class="fw-bold">${{ number_format($cuenta->total_estimado, 2) }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($cuenta->fecha_apertura)->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('cuentas.show', $cuenta) }}" class="btn btn-outline-info btn-sm">Ver</a>
                                <a href="{{ route('cuentas.edit', $cuenta) }}" class="btn btn-outline-primary btn-sm">Editar</a>
                                <form action="{{ route('cuentas.marcarPagada', $cuenta) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success btn-sm" onclick="return confirm('¿Marcar cuenta como pagada?')">Marcar como pagada</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-info text-center">
                No hay cuentas pendientes por pagar.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection