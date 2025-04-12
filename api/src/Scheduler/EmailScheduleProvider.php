<?php

namespace App\Scheduler;

use App\Scheduler\Message\SendEmail;
use App\Service\EmailService;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Component\Scheduler\RecurringMessage;

#[AsSchedule('email')]
class EmailScheduleProvider implements ScheduleProviderInterface
{
    private ?Schedule $schedule = null;

    public function __construct(
        private EmailService $emailService,
        private string $email,
    ) {}

    public function getSchedule(): Schedule
    {
        return $this->schedule ??= (new Schedule())
            ->with(
                RecurringMessage::cron(
                    '0 8 * * 1',
                    new SendEmail(
                        $this->email,
                        'Weekly Report',
                        content: ""
                    )
                )
            );
    }
}
