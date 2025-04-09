<?php

namespace App\DataFixtures;

use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ImageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $placeholders = [
            ['filename' => 'chat1.jpg', 'name' => 'Chat 1'],
            ['filename' => 'chat2.jpg', 'name' => 'Chat 2'],
            ['filename' => 'chat3.jpg', 'name' => 'Chat 3'],
            ['filename' => 'chat4.jpg', 'name' => 'Chat 4'],
            ['filename' => 'chat5.jpg', 'name' => 'Chat 5'],
            ['filename' => 'chat6.jpg', 'name' => 'Chat 6'],
        ];

        foreach ($placeholders as $data) {
            $image = new Image();
            $image->setFilename($data['filename']);
            $image->setName($data['name']);

            $manager->persist($image);
        }

        $manager->flush();
    }
}
