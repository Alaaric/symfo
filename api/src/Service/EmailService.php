<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Repository\StatRepository;

class EmailService
{
    public function __construct(
        private MailerInterface $mailer,
        private StatRepository $statRepository,
        private string $email
    ) {}

    public function sendWeeklyStatsEmail(): void
    {
        $statsTable = $this->generateStatsTable($this->getWeeklyStats());

        $this->sendEmail(
            $this->email,
            'Weekly Stats Report',
            '<h1>Weekly Stats Report</h1>' . $statsTable
        );
    }

    public function getWeeklyStats(): array
    {
        $currentWeek = (new \DateTime())->format('o-W');
        $stats = $this->statRepository->findTop20ByWeek($currentWeek);

        if (empty($stats)) {
            throw new \RuntimeException('No stats available for the current week.');
        }

        return $stats;
    }

    public function sendEmail(string $to, string $subject, string $content): void
    {
        $email = (new Email())
            ->to($to)
            ->subject($subject)
            ->html($content);

        $this->mailer->send($email);
    }

    public function generateStatsTable(array $stats): string
    {
        $table = '<table border="1" style="border-collapse: collapse; width: 100%;">';
        $table .= '<thead><tr><th>ID</th><th>Week</th><th>Views</th><th>Downloads</th><th>Image Name</th></tr></thead>';
        $table .= '<tbody>';

        foreach ($stats as $stat) {
            $table .= sprintf(
                '<tr><td>%d</td><td>%s</td><td>%d</td><td>%d</td><td>%s</td></tr>',
                $stat->getId(),
                $stat->getWeek(),
                $stat->getViews(),
                $stat->getDownload(),
                htmlspecialchars($stat->getImage()->getName(), ENT_QUOTES)
            );
        }

        $table .= '</tbody></table>';

        return $table;
    }
}
