<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class UnverifiedUsersReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Collection $users,
        public int $total
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[E-School237] Rapport - Rappels vérification email du ' . now()->format('d/m/Y'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.unverified-users-report',
        );
    }
}