<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Diario de Citas</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .doctor-info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #777; }
        .status { font-weight: bold; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ isset($isFullSchedule) && $isFullSchedule ? 'Agenda Completa de Citas' : 'Agenda de Citas Médicas' }}</h1>
        <p>Fecha de Reporte: {{ now()->format('d/m/Y') }}</p>
    </div>

    <div class="doctor-info">
        <p><strong>Doctor(a):</strong> {{ $doctor->user->name }}</p>
        <p><strong>Especialidad:</strong> {{ $doctor->specialty }}</p>
    </div>

    <h3>{{ isset($isFullSchedule) && $isFullSchedule ? 'Próximas Citas Programadas' : 'Resumen de la Jornada' }}</h3>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Paciente</th>
                <th>Motivo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appointment)
            <tr>
                <td>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</td>
                <td>{{ $appointment->patient->user->name }}</td>
                <td>{{ $appointment->reason ?? 'Consulta General' }}</td>
                <td class="status">{{ $appointment->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Este es un reporte automático generado por el Sistema de Citas Médicas.</p>
    </div>
</body>
</html>
