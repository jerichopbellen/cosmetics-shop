<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderPlaced extends Mailable
{
    use Queueable;

    public function __construct(public $order) {}

    public function content(): Content
    {
        return new Content(view: 'emails.order_placed');
    }

    public function attachments(): array
    {
        // Use the same variables as your myOrders logic
        $pdf = Pdf::loadView('pdf.receipt', ['order' => $this->order]);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'Glow_Receipt_' . $this->order->order_number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}