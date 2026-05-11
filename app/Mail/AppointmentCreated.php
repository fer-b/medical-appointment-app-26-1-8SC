<?php

namespace App\Mail;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class AppointmentCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(Appointment $appointment, User $user)
    {
        $this->appointment = $appointment;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmación de Cita Médica',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment-created',
            with: [
                'appointment' => $this->appointment,
                'user' => $this->user,
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
        // Generate PDF in memory
        $pdf = Pdf::loadView('pdfs.appointment-receipt', ['appointment' => $this->appointment]);
        $pdfContent = $pdf->output();

        return [
            Attachment::fromData(fn () => $pdfContent, 'Comprobante_Cita_'.$this->appointment->id.'.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
