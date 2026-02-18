<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Ngo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $ngo;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param Ngo $ngo
     */
    public function __construct(User $user, Ngo $ngo)
    {
        $this->user = $user;
        $this->ngo = $ngo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welcome to Our Platform - NGO Registration Details')
            ->view('emails.welcome')
            ->with([
                'userName' => $this->user->name,
                'userEmail' => $this->user->email,
                'ngo' => $this->ngo,
                'loginUrl' => route('login'),
                'passwordResetUrl' => route('password.request'),
            ]);
    }
}
