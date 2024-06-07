<?php

namespace Modules\AuthModule\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

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

    public function build()
    {
        $emailTitle = $this->replacePlaceholders(setting('authmodule.emails.forgot_password.title'));
        $emailSubject = $this->replacePlaceholders(setting('authmodule.emails.forgot_password.subject'));

        return $this->to($this->email, $emailTitle)
            ->subject($emailSubject)
            ->view('authmodule::emails.forgot-password', [
                'username' => $this->username,
                'firstName' => $this->firstName,
                'lastName' => $this->lastName,
                'resetLink' => $this->resetLink,
            ]);
    }

    public function replacePlaceholders($string)
    {
        $replacementArray = [
            '{username}' => $this->username,
            '{firstName}' => $this->firstName,
            '{lastName}' => $this->lastName,
            '{resetLink}' => $this->resetLink,
        ];

        return str_replace(array_keys($replacementArray), array_values($replacementArray), $string);
    }
}
