<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class OtpMail extends Mailable
{
    public string $otp;

    public function __construct(string $otp)
    {
        $this->otp = $otp;
    }  // ← kurung ini yang hilang!

    public function envelope(): Envelope
    {
        return new Envelope(subject: "Kode OTP D'Kost");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.otp');
    }
}