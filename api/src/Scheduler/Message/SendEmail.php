<?php

namespace App\Scheduler\Message;

use Symfony\Component\Messenger\Attribute\AsMessage;


#[AsMessage('async')]
class SendEmail {
    public function __construct(
        public readonly string $email,
        public readonly string $subject,
        public readonly string $content
    )
    {}
    
}