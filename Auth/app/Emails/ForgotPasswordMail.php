<?php

namespace Modules\Auth\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;

    public $username;

    public $firstName;

    public $lastName;

    public $resetLink;

    public function __construct($email, $username, $firstName, $lastName, $resetLink)
    {
        $this->email = $email;
        $this->username = $username;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->resetLink = $resetLink;
    }

    private function replacePlaceholders($string)
    {
        $replacementArray = [
            '{username}' => $this->username,
            '{firstName}' => $this->firstName,
            '{lastName}' => $this->lastName,
            '{resetLink}' => $this->resetLink,
        ];

        return str_replace(array_keys($replacementArray), array_values($replacementArray), $string);
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        $emailTitle = $this->replacePlaceholders(settings('auth.emails.forgot_password.title', config('auth.emails.forgot_password.title')));
        $emailSubject = $this->replacePlaceholders(settings('auth.emails.forgot_password.subject', config('auth.emails.forgot_password.subject')));

        return $this->to($this->email, $emailTitle)
            ->subject($emailSubject)
            ->view('auth::emails.forgot-password', [
                'username' => $this->username,
                'firstName' => $this->firstName,
                'lastName' => $this->lastName,
                'resetLink' => $this->resetLink,
            ]);
    }
}
