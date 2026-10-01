<?php

namespace Modules\Auth\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;
    public $expiration;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $token, $expiration)
    {
        $this->user = $user;
        $this->token = $token;
        $this->expiration = $expiration;
        $this->subject = __('auth::emails.forgot_password.subject');
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->view('auth::components.emails.forgot-password-mail', [
            'user' => $this->user,
            'token' => $this->token,
            'expiration' => $this->expiration,
        ]);
    }
}
