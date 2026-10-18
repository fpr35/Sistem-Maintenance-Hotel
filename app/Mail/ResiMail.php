<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class ResiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $resi;

    public function __construct($resi)
    {
        $this->resi = $resi;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('mail.from.address'), config('mail.from.name')), // diatur lewat MAIL_FROM_* di .env
            subject: 'Konfirmasi Pembelian',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'admin.email.resi',
            with: [
                'resi' => $this->resi,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
