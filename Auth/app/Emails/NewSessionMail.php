<?php

namespace Modules\Auth\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewSessionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;

    public $username;

    public $firstName;

    public $lastName;
    public $ipAddress;
    public $userAgent;
    public $loginTime;

    public function __construct($email, $username, $firstName, $lastName)
    {
        $this->email = $email;
        $this->username = $username;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->ipAddress = request()->ip();
        $this->userAgent = request()->userAgent();
        $this->loginTime = now()->format('Y-m-d H:i:s');
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        $emailTitle = $this->replacePlaceholders(settings('auth.emails.new_session.title', config('auth.emails.new_session.title')));
        $emailSubject = $this->replacePlaceholders(settings('auth.emails.new_session.subject', config('auth.emails.new_session.subject')));

        return $this->to($this->email, $emailTitle)
            ->subject($emailSubject)
            ->view('auth::emails.new-session', [
                'username' => $this->username,
                'firstName' => $this->firstName,
                'lastName' => $this->lastName,
                'ipAddress' => $this->ipAddress,
                'userAgent' => $this->userAgent,
                'loginTime' => $this->loginTime,
            ]);
    }

    private function replacePlaceholders($string)
    {
        $replacementArray = [
            '{username}' => $this->username,
            '{first_name}' => $this->firstName,
            '{last_name}' => $this->lastName,
            '{ip_address}' => $this->ipAddress,
            '{user_agent}' => $this->userAgent,
            '{login_time}' => $this->loginTime,
        ];

        return str_replace(array_keys($replacementArray), array_values($replacementArray), $string);
    }
}
