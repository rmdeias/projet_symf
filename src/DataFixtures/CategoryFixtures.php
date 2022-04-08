<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        // create 20 products! Bam!
        for ($i = 0; $i < 20; $i++) {
            $category = new Category();
            $category->setname('cat '.$i);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
