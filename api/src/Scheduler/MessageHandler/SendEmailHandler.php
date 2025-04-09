<?php

namespace App\Scheduler\MessageHandler;

use App\Scheduler\Message\SendEmail;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendEmailHandler
{
    public function __construct(private MailerInterface $mailer) {}

    public function __invoke(SendEmail $message): void
    {
        $email = (new Email())
            ->to($message->email)
            ->subject($message->subject)
            ->text($message->content);

        $this->mailer->send($email);
    }
}