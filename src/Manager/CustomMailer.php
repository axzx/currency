<?php

namespace App\Manager;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class CustomMailer
{
    private $mailer;
    private $from;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->from = $_ENV['EMAIL_FROM'];
    }

    public function send(string $to, string $subject, string $text): void
    {
        $email = (new Email())
            ->from($this->from)
            ->to($to)
            ->subject($subject)
            ->text($text)
        ;

        $this->mailer->send($email);
    }
}
