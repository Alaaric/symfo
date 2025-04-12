<?php

namespace App\Scheduler\MessageHandler;

use App\Scheduler\Message\SendEmail;
use App\Service\EmailService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendEmailHandler
{
    public function __construct(private EmailService $emailService) {}

    public function __invoke(SendEmail $message): void
    {
        $statsTable = $this->emailService->generateStatsTable(
            $this->emailService->getWeeklyStats()
        );
        $this->emailService->sendEmail(
            $message->email,
            $message->subject,
            '<h1>Weekly Stats Report</h1>' . $statsTable
        );
    }
}
