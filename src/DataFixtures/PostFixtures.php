<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\AsciiSlugger;

class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $time = new \DateTime();
        $slugger = new AsciiSlugger();

        // create 20 products! Bam!
        for ($i = 0; $i < 20; $i++) {
            $post = new Post();
            $post->setTitle('tiltléé_$èèAA '.$i);
            $post->setSlug($slugger->slug( $post->getTitle()));
            $post->setContent('contenu' . $i);
            $post->setDatePublication($time);
            $manager->persist($post);
        }

        $manager->flush();
    }
}
