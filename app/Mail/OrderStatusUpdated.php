<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderStatusUpdated extends Mailable
{
    use Queueable;

    public function __construct(public $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Update: ' . $this->order->status . ' (#' . $this->order->order_number . ')',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.status_updated',
        );
    }

    public function attachments(): array
    {
        // 10pts: Attach the PDF receipt to every status update email
        $pdf = Pdf::loadView('pdf.receipt', ['order' => $this->order]);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'Glow_Receipt_' . $this->order->order_number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}