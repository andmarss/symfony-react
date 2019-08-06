<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /**
         * @var BlogPost $blogPost
         */
        $blogPost = new BlogPost();
        $blogPost->setTitle('A first fixture post!');
        $blogPost->setPublished(new \DateTime('2019-08-07 12:00:00'));
        $blogPost->setContent('Post text');
        $blogPost->setAuthor('Foo Bar');
        $blogPost->setSlug('a-first-post');

        $manager->persist($blogPost);

        /**
         * @var BlogPost $blogPost
         */
        $blogPost = new BlogPost();
        $blogPost->setTitle('A second fixture post!');
        $blogPost->setPublished(new \DateTime('2019-08-07 13:00:00'));
        $blogPost->setContent('Second post text');
        $blogPost->setAuthor('Foo Bar');
        $blogPost->setSlug('a-second-post');

        $manager->persist($blogPost);

        $manager->flush();
    }
}
