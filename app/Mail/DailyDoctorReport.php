<?php

namespace App\Mail;

use App\Models\Doctor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyDoctorReport extends Mailable
{
    use Queueable, SerializesModels;

    public $appointments;
    public $doctor;
    public $isFullSchedule;

    /**
     * Create a new message instance.
     */
    public function __construct(Doctor $doctor, $appointments, $isFullSchedule = false)
    {
        $this->doctor = $doctor;
        $this->appointments = $appointments;
        $this->isFullSchedule = $isFullSchedule;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->isFullSchedule 
            ? 'Tu Agenda Completa de Citas' 
            : 'Tu agenda de citas para hoy - ' . now()->format('d/m/Y');

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.daily-doctor-report',
            with: [
                'doctor' => $this->doctor,
                'appointments' => $this->appointments,
                'isFullSchedule' => $this->isFullSchedule,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdfs.daily-doctor-report', [
            'doctor' => $this->doctor,
            'appointments' => $this->appointments,
            'isFullSchedule' => $this->isFullSchedule,
        ]);

        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(fn () => $pdf->output(), 'Agenda_Citas_' . now()->format('d-m-Y') . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
