<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifikasiAkunMitraMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mitra;

    public function __construct($mitra)
    {
        $this->mitra = $mitra;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verifikasi Akun Mitra Mail',
        );
    }


    public function content(): Content
    {
        return new Content(
            view: 'admin.email.verifikasi_akun_mitra',
            with: [
                'mitra' => $this->mitra,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
