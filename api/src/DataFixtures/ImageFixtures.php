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
            ['filename' => 'chat1.jpg', 'name' => 'Chat1.jpg'],
            ['filename' => 'chat2.jpg', 'name' => 'Chat2.jpg'],
            ['filename' => 'chat3.jpg', 'name' => 'Chat3.jpg'],
            ['filename' => 'chat4.jpg', 'name' => 'Chat4.jpg'],
            ['filename' => 'chat5.jpg', 'name' => 'Chat5.jpg'],
            ['filename' => 'chat6.jpg', 'name' => 'Chat6.jpg'],
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
