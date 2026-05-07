<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otp;
    public string $subjectLine;

    // ← parameter kedua subject (default agar tidak breaking change)
    public function __construct(string $otp, string $subject = 'Kode OTP D\'Kost')
    {
        $this->otp         = $otp;
        $this->subjectLine = $subject;
    }

    public function build(): self
    {
        return $this->subject($this->subjectLine)
                    ->view('emails.otp');
    }
}