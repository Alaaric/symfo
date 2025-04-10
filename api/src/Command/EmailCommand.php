<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsCommand(
    name: 'lc:email',
    description: 'Send email with weekly stats report',
)]
class EmailCommand extends Command
{
    
    public function __construct(
        private MailerInterface $mailer,
        private string $email
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = (new Email())
            ->to($this->email)
            ->subject('test Email')
            ->text('placeholder text'); 
        $this->mailer->send($email);

        $io->success('Email sent');

        return Command::SUCCESS;
    }
}
