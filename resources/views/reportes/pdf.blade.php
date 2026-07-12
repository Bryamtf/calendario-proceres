<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #26282B;
        }

        h1 {
            font-size: 16px;
            margin-bottom: 2px;
        }

        p.subtitulo {
            color: #6b7280;
            margin-top: 0;
            margin-bottom: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #FAF8F4;
            text-align: left;
            padding: 6px 8px;
            border-bottom: 2px solid #E4DFD3;
            font-size: 10px;
            text-transform: uppercase;
        }

        td {
            padding: 6px 8px;
            border-bottom: 1px solid #E4DFD3;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 9px;
        }

        .totales {
            margin-top: 16px;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <h1>{{ \App\Models\ConfiguracionSistema::obtener()->nombre_barrio }}</h1>
    <p class="subtitulo">Reporte de actividades — generado {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>Actividad</th>
                <th>Organización</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th style="text-align:right">Presupuesto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($actividades as $actividad)
                <tr>
                    <td>{{ $actividad->nombre }}</td>
                    <td>{{ $actividad->organizacion->nombre }}</td>
                    <td>{{ $actividad->fecha->format('d/m/Y') }}</td>
                    <td>{{ $actividad->estadoActual->nombre }}</td>
                    <td style="text-align:right">${{ number_format($actividad->montoTotalSolicitado(), 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="totales">Total de actividades: {{ $actividades->count() }} · Presupuesto total solicitado:
        ${{ number_format($actividades->sum(fn($a) => $a->montoTotalSolicitado()), 2) }}</p>
</body>

</html>
