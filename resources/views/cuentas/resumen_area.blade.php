@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --primary: #556ee6;
    --accent: #37c5ab;
    --bg-dark: #232734;
    --bg-light: #f6f7fb;
    --table-header-dark: #23283a;
    --table-header-light: #e9ecef;
    --txt-dark: #fff;
    --txt-light: #23232a;
    --muted: #bdbdbd;
    --gold: #e2c275;
    --border-dark: #353b50;
    --border-light: #e3e3e3;
    --green: #20c997;
    --red: #e74c3c;
    --shadow: 0 4px 48px 0 rgba(0,0,0,0.12);
    --radius: 1.1rem;
}
body, .container {
    font-family: 'Montserrat', Arial, sans-serif;
    font-size: 1.08rem;
    /* background: var(--bg-dark); */
    color: var(--txt-dark);
    transition: background .4s, color .2s;
}
[data-theme="light"] body, [data-theme="light"] .container {
    background: var(--bg-light) !important;
    color: var(--txt-light);
}
.section-title {
    font-size: 2.2rem;
    color: var(--primary);
    font-weight: 800;
    text-align: center;
    margin-bottom: 2.1rem;
    letter-spacing: 1.1px;
    text-shadow: 0 2px 16px #0002;
}
.filter-card {
    background: var(--bg-dark);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    padding: 1.7rem 2.5rem 1.3rem 2.5rem;
    max-width: 80%;
    margin: 0 auto 2.8rem auto;
    border: 1.5px solid var(--primary);
    position: relative;
    z-index: 2;
}
[data-theme="light"] .filter-card {
    background: var(--bg-light);
    border-color: var(--primary);
}
.filter-form label {
    color: var(--primary);
    font-weight: 600;
    letter-spacing: 0.6px;
    font-size: 1.06em;
    margin-bottom: .3em;
}
.filter-form input[type="date"] {
    background: var(--table-header-dark);
    color: var(--primary);
    border: 1.5px solid var(--primary);
    border-radius: .5rem;
    font-weight: bold;
    padding: .48rem 1.1rem;
    font-size: 1.02em;
    transition: all .15s;
    box-shadow: 0 1.5px 6px #556ee640 inset;
}
[data-theme="light"] .filter-form input[type="date"] {
    background: var(--table-header-light);
    color: var(--primary);
    border: 1.5px solid var(--primary);
}
.export-btn {
    background: var(--green);
    color: #fff !important;
    border: none;
    border-radius: .55rem;
    font-weight: bold;
    font-size: 1.06em;
    padding: .52rem 1.5rem;
    box-shadow: 0 2px 8px 0 #1dd17c30;
    margin-left: .3em;
    transition: background .2s;
}
.export-btn:hover { background: #159870; }
.filter-btn {
    background: var(--primary);
    color: #fff !important;
    border: none;
    border-radius: .55rem;
    font-weight: bold;
    font-size: 1.05em;
    padding: .52rem 1.5rem;
    margin-right: .3em;
    transition: background .2s;
}
.filter-btn:hover { background: #2340b2; }
.resumen-areas-wrapper {
    display: flex;
    flex-direction: column;
    gap: 2.5rem;
    align-items: center;
    justify-content: center;
}
.resumen-card {
    background: var(--bg-dark);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    margin-bottom: 0;
    padding: 0;
    width: 100%;
    max-width: 950px;
    border: 1.4px solid var(--primary);
    position: relative;
}
[data-theme="light"] .resumen-card {
    background: var(--bg-light);
    border-color: var(--primary);
}
.resumen-header {
    background: linear-gradient(90deg, var(--primary) 70%, var(--accent) 100%);
    color: #fff;
    font-size: 1.3rem;
    font-weight: 700;
    padding: 1.15rem 2rem 1rem 2rem;
    display: flex;
    align-items: center;
    border-top-left-radius: var(--radius);
    border-top-right-radius: var(--radius);
    border-bottom: 1.5px solid var(--primary);
    letter-spacing: 0.3px;
}
.resumen-header .icon-area {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: #eaf0ff;
    color: var(--primary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.7rem;
    margin-right: 1.1rem;
    box-shadow: 0 2px 12px 0 #556ee620;
    border: 1.2px solid var(--primary);
}
.resumen-table {
    margin-bottom: 0;
    border-radius: 0 0 var(--radius) var(--radius);
    overflow: hidden;
    min-width: 100%;
}
.resumen-table th, .resumen-table td {
    vertical-align: middle;
    border-color: var(--border-dark);
}
.resumen-table th {
    background: var(--table-header-dark);
    color: var(--primary);
    font-weight: 800;
    font-size: 1.08em;
    border-top: 2.5px solid var(--primary);
    text-align: center;
    letter-spacing: .5px;
}
[data-theme="light"] .resumen-table th {
    background: var(--table-header-light);
    color: var(--primary);
    border-color: var(--border-light);
}
.resumen-table tbody tr {
    background: var(--bg-card);
    text-align: center;
    font-size: 1.06em;
    transition: background .14s;
}
[data-theme="light"] .resumen-table tbody tr {
    background: var(--bg-light);
}
.resumen-table tbody tr:nth-child(even) {
    background: var(--bg-table-row-alt);
}
[data-theme="light"] .resumen-table tbody tr:nth-child(even) {
    background: #f2f2f8;
}
.area-total-row td {
    font-weight: 900;
    color: var(--primary);
    background: #131a33 !important;
    font-size: 1.13em;
    border-bottom: 2.5px solid var(--primary);
    text-align: right;
    letter-spacing: .2px;
    padding: 0.5rem;
}
[data-theme="light"] .area-total-row td {
    background: #e1e7fa !important;
    border-bottom: 2.5px solid var(--primary);
}
.table-details {
    text-align: left;
    font-size: .98em;
    color: var(--muted);
    padding: .8em 0 0 0;
}
.area-graph {
    width: 40%;
    max-width: 520px;
    margin: 1.5rem auto 0 auto;
    padding: 1rem 0 0 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
@media (max-width: 1000px) {
    .resumen-card, .filter-card { max-width: 100%; }
}
@media (max-width: 600px) {
    .resumen-header { font-size: 1.08em; padding: .8rem .7rem; }
    .icon-area { width: 28px; height: 28px; font-size: 1.15em; margin-right: .5em; }
    .filter-card { padding: 1rem; }
    .area-graph { max-width: 100%; }
}
::-webkit-scrollbar-thumb { background: #556ee660; }
::-webkit-scrollbar-track { background: #232734;}
</style>
<div class="container pt-4">
    <div class="section-title">
        <i class="bi bi-bar-chart-fill"></i> Resumen de Ventas por Área
    </div>
    <div class="filter-card">
        <form method="GET" class="row g-3 filter-form align-items-end justify-content-center" style="display: flex;
    flex-direction: row;
    align-content: center;
    justify-content: center;
    justify-content: space-around;
    align-items: center;">
            <div class="col-md-3 col-12">
                <label for="fecha_inicio" class="col-form-label">Desde:</label>
                <input type="date" name="fecha_inicio" class="form-control" value="{{ $fechaInicio }}">
            </div>
            <div class="col-md-3 col-12">
                <label for="fecha_fin" class="col-form-label">Hasta:</label>
                <input type="date" name="fecha_fin" class="form-control" value="{{ $fechaFin }}">
            </div>
            <div class="col-md-2 col-12 text-center">
                <button class="btn filter-btn w-100"><i class="bi bi-funnel-fill"></i> Filtrar</button>
            </div>
            <div style="display:flex;gap:.5rem;">
                <button class="btn filter-btn w-100"><a href="{{ route('cuentas.resumenArea') }}" class="btn btn-clear" id="limpiarBtn"><i class="bi bi-x-circle"></i> Limpiar</a></button>
            </div>
            <div class="col-md-2 col-12 text-center">
                <a href="{{ route('cuentas.resumenArea', ['export' => 1] + Request::all()) }}" class="btn export-btn w-100"><i class="bi bi-file-earmark-excel"></i> Exportar Excel</a>
            </div>
        </form>
    </div>
    <div class="resumen-areas-wrapper">
    @foreach($areas as $area_id => $area_nombre)
        <div class="card resumen-card">
            <div class="resumen-header">
                <span class="icon-area">
                    @if(str_contains(strtolower($area_nombre), 'carne'))
                        <i class="bi bi-fire"></i>
                    @elseif(str_contains(strtolower($area_nombre), 'barra'))
                        <i class="bi bi-cup-straw"></i>
                    @elseif(str_contains(strtolower($area_nombre), 'cachapa'))
                        <i class="bi bi-egg-fried"></i>
                    @elseif(str_contains(strtolower($area_nombre), 'cocina'))
                        <i class="bi bi-egg"></i>
                    @else
                        <i class="bi bi-grid"></i>
                    @endif
                </span>
                {{ $area_nombre }}
            </div>
            <div class="card-body px-3 py-2">
                @php $area_total = 0; @endphp
                @if(!empty($resumen[$area_id]))
                <div class="table-responsive">
                    <table class="table resumen-table mb-0">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Unidad</th>
                                <th>Precio Unitario</th>
                                <th>Cantidad Vendida</th>
                                <th>Total $</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resumen[$area_id] as $prod)
                            <tr>
                                <td style="text-align:left;">{{ $prod['nombre'] }}</td>
                                <td>{{ $prod['categoria'] }}</td>
                                <td>{{ $prod['unidad_medida'] }}</td>
                                <td>${{ number_format($prod['precio_venta'],2) }}</td>
                                <td>{{ $prod['cantidad'] }}</td>
                                <td>${{ number_format($prod['subtotal'],2) }}</td>
                                @php $area_total += $prod['subtotal']; @endphp
                            </tr>
                            @endforeach
                            <tr class="area-total-row">
                                <td colspan="5">Total Vendido en {{ $area_nombre }}</td>
                                <td>${{ number_format($area_total,2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="area-graph">
                    <canvas id="bar-graph-{{ $area_id }}"></canvas>
                    <canvas id="pie-graph-{{ $area_id }}" style="margin-top:25px;"></canvas>
                </div>
                @else
                <div class="p-3 text-center text-muted">No hubo ventas en esta área.</div>
                @endif
            </div>
        </div>
    @endforeach
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
window.jsonGraficos = @json($jsonGraficos);

document.addEventListener('DOMContentLoaded', function() {
    Object.entries(window.jsonGraficos).forEach(([area_id, datos]) => {
        if (datos.nombres.length === 0) return;
        // Bar Chart
        const ctxBar = document.getElementById('bar-graph-' + area_id);
        if(ctxBar) {
            new Chart(ctxBar.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: datos.nombres,
                    datasets: [{
                        label: 'Cantidad Vendida',
                        data: datos.cantidades,
                        backgroundColor: 'rgba(85,110,230,0.65)',
                        borderColor: 'rgba(85,110,230,1)',
                        borderWidth: 2,
                        borderRadius: 7
                    }]
                },
                options: {
                    plugins: { legend: { display: false }},
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#e2e2e2' }},
                        x: { grid: { color: '#e2e2e2' }}
                    }
                }
            });
        }
        // Pie Chart
        const ctxPie = document.getElementById('pie-graph-' + area_id);
        if(ctxPie) {
            new Chart(ctxPie.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: datos.nombres,
                    datasets: [{
                        label: 'Ventas $',
                        data: datos.ventas,
                        backgroundColor: [
                            '#556ee6','#37c5ab','#e2c275','#ff7675','#fdcb6e','#6c5ce7','#00b894','#fd79a8','#636e72'
                        ]
                    }]
                },
                options: {
                    plugins: { legend: { display: true, position: 'bottom' } }
                }
            });
        }
    });
});
</script>
@endsection