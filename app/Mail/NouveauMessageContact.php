<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NouveauMessageContact extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $contactMessage)
    {
    }

    public function build()
    {
        return $this->subject('Nouveau message depuis ton portfolio : ' . ($this->contactMessage->sujet ?: 'Sans sujet'))
            ->replyTo($this->contactMessage->email, $this->contactMessage->nom)
            ->view('emails.nouveau-message-contact');
    }
}
