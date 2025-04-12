<?php

namespace App\Factory;

use App\Entity\Image;
use App\Entity\Stat;

class StatFactory
{
    public function createWithViewStat(Image $image, string $week): Stat
    {
        $stat = new Stat();
        $stat->setImage($image);
        $stat->setWeek($week);
        $stat->setViews(1);

        return $stat;
    }

    public function createWithDownloadStat(Image $image, string $week): Stat
    {
        $stat = new Stat();
        $stat->setImage($image);
        $stat->setWeek($week);
        $stat->setDownload(1);

        return $stat;
    }
}