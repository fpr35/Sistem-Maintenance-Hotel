<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// ============================================================================
// Email notifikasi untuk admin (laporan baru, pendaftaran mitra baru, dll).
// Hanya membawa data teks (bukan model) agar aman dikirim lewat antrean.
// ============================================================================
class NotifikasiAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param string $judul  Judul email
     * @param string $pesan  Paragraf penjelasan singkat
     * @param array  $detail Pasangan label => nilai yang ditampilkan sebagai tabel
     * @param string $link   URL tombol aksi
     * @param string $labelLink Teks tombol aksi
     */
    public function __construct(
        public string $judul,
        public string $pesan,
        public array $detail,
        public string $link,
        public string $labelLink,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Maintenance Hotel] ' . $this->judul,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'admin.email.notifikasi_admin',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
