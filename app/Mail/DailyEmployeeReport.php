<?php

namespace App\Mail;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyEmployeeReport extends Mailable
{
    use Queueable, SerializesModels;

    public $orders;
    public $employee;
    public $isFullSchedule;

    /**
     * Create a new message instance.
     */
    public function __construct(Employee $employee, $orders, $isFullSchedule = false)
    {
        $this->employee = $employee;
        $this->orders = $orders;
        $this->isFullSchedule = $isFullSchedule;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->isFullSchedule 
            ? 'Tu Agenda Completa de Pedidos' 
            : 'Tu agenda de pedidos para hoy - ' . now()->format('d/m/Y');

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
            view: 'emails.daily-employee-report',
            with: [
                'employee' => $this->employee,
                'orders' => $this->orders,
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
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdfs.daily-employee-report', [
            'employee' => $this->employee,
            'orders' => $this->orders,
            'isFullSchedule' => $this->isFullSchedule,
        ]);

        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(fn () => $pdf->output(), 'Agenda_Pedidos_' . now()->format('d-m-Y') . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
