<?php

namespace App\Scheduler;

use App\Scheduler\Message\SendEmail;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;
use Symfony\Component\Scheduler\RecurringMessage;

#[AsSchedule('email')]
class EmailScheduleProvider implements ScheduleProviderInterface
{
    private ?Schedule $schedule = null;

    public function __construct(private string $email) {}

    public function getSchedule(): Schedule
    {
        return $this->schedule ??= (new Schedule())
        ->with(
            RecurringMessage::every('2 minutes', 
                new SendEmail(
                    $this->email,
                    'Weekly repport',
                    'This is your weekly stat Report.'
                )
            )
        );
    }
}