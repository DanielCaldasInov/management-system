<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SupplierInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Invoice $invoice) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Document received: Invoice '.$this->invoice->reference,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.supplier_invoice',
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        if ($this->invoice->attachment_path) {
            $attachments[] = Attachment::fromStorageDisk('public', $this->invoice->attachment_path);
        }

        return $attachments;
    }
}
