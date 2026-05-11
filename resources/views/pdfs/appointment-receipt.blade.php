<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Cita</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.6; }
        .container { width: 100%; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .header { text-align: center; border-bottom: 2px solid #2563eb; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #1e40af; }
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .details-table th, .details-table td { padding: 10px; border-bottom: 1px solid #eee; text-align: left; }
        .details-table th { background-color: #f8fafc; font-weight: bold; width: 30%; }
        .footer { text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #eee; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Comprobante de Cita Médica</h1>
            <p>Hospital Central</p>
        </div>
        
        <table class="details-table">
            <tr>
                <th>Paciente</th>
                <td>{{ $appointment->patient->user->name }}</td>
            </tr>
            <tr>
                <th>Médico (Especialidad)</th>
                <td>Dr/Dra. {{ $appointment->doctor->user->name }} ({{ $appointment->doctor->specialty }})</td>
            </tr>
            <tr>
                <th>Fecha de la Cita</th>
                <td>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Hora</th>
                <td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</td>
            </tr>
            <tr>
                <th>Motivo de Consulta</th>
                <td>{{ $appointment->reason ?? 'No especificado' }}</td>
            </tr>
        </table>
        
        <div class="footer">
            <p>Por favor, asista 15 minutos antes de la hora programada.</p>
            <p>Si necesita cancelar, comuníquese con al menos 24 horas de anticipación.</p>
        </div>
    </div>
</body>
</html>
