<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\EmailService;

#[AsCommand(
    name: 'lc:email',
    description: 'Send email with weekly stats report',
)]
class EmailCommand extends Command
{

    public function __construct(
        private EmailService $emailService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->emailService->sendWeeklyStatsEmail();
            $io->success('Email sent');
        } catch (\RuntimeException $e) {
            $io->warning($e->getMessage());
        }

        return Command::SUCCESS;
    }
}
