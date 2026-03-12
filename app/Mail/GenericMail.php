<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\MailTemplate;

class GenericMail extends Mailable
{
    use Queueable, SerializesModels;

    public MailTemplate $template;
    public array $data; // <-- tableau des variables à injecter

    /**
     * Create a new message instance.
     */
    public function __construct(MailTemplate $template, array $data = [])
    {
        $this->template = $template;
        $this->data = $data;
    }

    public function build()
    {
        // On remplace les placeholders dans le HTML
        $html = $this->template->html_content;

        foreach ($this->data as $key => $value) {
            // On remplace {{key}} par la valeur
            $html = str_replace('{{'.$key.'}}', $value, $html);
        }
        return $this
            ->subject($this->template->subject)
            ->html($html); // Utilise le HTML du template
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->template->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
