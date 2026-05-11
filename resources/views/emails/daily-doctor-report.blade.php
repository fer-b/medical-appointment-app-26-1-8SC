<div>
    @if(isset($isFullSchedule) && $isFullSchedule)
        <h2>Tu Agenda Completa de Citas</h2>
        <p>Hola, Dr/Dra. {{ $doctor->user->name }}.</p>
        <p>A continuación, se presenta la lista completa de sus próximas citas programadas:</p>
    @else
        <h2>Tus Citas Programadas para Hoy</h2>
        <p>Hola, Dr/Dra. {{ $doctor->user->name }}.</p>
        <p>A continuación, se presenta la lista de pacientes que atenderá el día de hoy:</p>
    @endif
    
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f3f4f6;">
                <th>Fecha</th>
                <th>Hora</th>
                <th>Paciente</th>
                <th>Motivo de Consulta</th>
            </tr>
        </thead>
        <tbody>
            @forelse($appointments as $appointment)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</td>
                    <td>{{ $appointment->patient->user->name }}</td>
                    <td>{{ $appointment->reason ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">No tiene citas programadas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
