<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;
    public $resetUrl;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
        $this->resetUrl = url('/reset-password/' . $token . '?email=' . urlencode($email));
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Your Dawalo Password',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reset_password_email',
        );
    }
}
