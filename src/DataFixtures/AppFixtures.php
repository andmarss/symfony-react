<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {

        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);

        $this->loadPosts($manager);
    }

    public function loadPosts(ObjectManager $manager)
    {
        /**
         * @var User $user
         */
        $user = $this->getReference('user_admin');

        /**
         * @var BlogPost $blogPost
         */
        $blogPost = new BlogPost();
        $blogPost->setTitle('A first fixture post!');
        $blogPost->setPublished(new \DateTime('2019-08-07 12:00:00'));
        $blogPost->setContent('Post text');
        $blogPost->setAuthor($user);
        $blogPost->setSlug('a-first-post');

        $manager->persist($blogPost);

        /**
         * @var BlogPost $blogPost
         */
        $blogPost = new BlogPost();
        $blogPost->setTitle('A second fixture post!');
        $blogPost->setPublished(new \DateTime('2019-08-07 13:00:00'));
        $blogPost->setContent('Second post text');
        $blogPost->setAuthor($user);
        $blogPost->setSlug('a-second-post');

        $manager->persist($blogPost);

        $manager->flush();
    }

    public function loadComment(ObjectManager $manager)
    {

    }

    public function loadUsers(ObjectManager $manager)
    {
        /**
         * @var User $user
         */
        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('admin@blog.com');
        $user->setName('Foo Bar');

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user, 'secret'
        ));

        $this->addReference('user_admin', $user);

        $manager->persist($user);
        $manager->flush();
    }
}
