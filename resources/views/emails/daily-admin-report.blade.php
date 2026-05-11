<div>
    <h2>Reporte Diario de Citas</h2>
    <p>Hola, administrador.</p>
    <p>A continuación, se presenta la lista general de citas médicas programadas para el día de hoy:</p>
    
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f3f4f6;">
                <th>Hora</th>
                <th>Paciente</th>
                <th>Médico</th>
                <th>Motivo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($appointments as $appointment)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</td>
                    <td>{{ $appointment->patient->user->name }}</td>
                    <td>Dr/Dra. {{ $appointment->doctor->user->name }}</td>
                    <td>{{ $appointment->reason ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">No hay citas programadas para hoy.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
