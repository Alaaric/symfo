<?php

namespace App\Command;

use App\Repository\StatRepository;
use DateTime;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'lc:excel',
    description: 'Export the top 20 weekly stats to an Excel file',
)]
class ExportWeeklyStatsCommand extends Command
{
    public function __construct(
        private StatRepository $statRepository,
        private ParameterBagInterface $params
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
                'out', 
                null, 
                InputOption::VALUE_REQUIRED, 
                'Output filename for Excel export', 
                'weekly_stats.xlsx'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $fileName = $input->getOption('out');

        $week = (new DateTime())->format('o-\WW');

        $topStats = $this->statRepository->findTop20ByWeek($week);

        if (!$topStats) {
            $io->warning("Aucune stat trouvée pour la semaine $week.");
            return Command::SUCCESS;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Top 20 Stats');
        $sheet->setCellValue('A1', 'Image ID');
        $sheet->setCellValue('B1', 'Nom Image');
        $sheet->setCellValue('C1', 'Semaine');
        $sheet->setCellValue('D1', 'Vues');

        $row = 2;
        foreach ($topStats as $stat) {
            $image = $stat->getImage();
            $sheet->setCellValue("A{$row}", $image?->getId());
            $sheet->setCellValue("B{$row}", $image?->getName());
            $sheet->setCellValue("C{$row}", $stat->getWeek());
            $sheet->setCellValue("D{$row}", $stat->getViews());
            $row++;
        }

        $projectDir = $this->params->get('kernel.project_dir');
        $exportDir = $projectDir . '/public/exports';

        $writer = new Xlsx($spreadsheet);
        $writer->save("$exportDir/$fileName.xlsx");

        $io->success("Export du fichier terminé a l'emplacement: public/exports/$fileName.xlsx");

        return Command::SUCCESS;
    }
}