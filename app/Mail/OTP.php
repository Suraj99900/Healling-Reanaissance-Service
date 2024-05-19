<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OTP extends Mailable
{
    use Queueable, SerializesModels;
    private $aData = array();

    /**
     * Create a new message instance.
     */
    public function __construct($aData)
    {
        $this->aData = $aData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->aData['title'],
        );
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('emails.html')
            ->with($this->aData);
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
