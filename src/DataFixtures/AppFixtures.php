<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\{Generator, Factory};

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var Generator $faker
     */
    private $faker;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;

        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);

        $this->loadPosts($manager);

        $this->loadComment($manager);
    }

    public function loadPosts(ObjectManager $manager)
    {
        /**
         * @var User $user
         */
        $user = $this->getReference('user_admin');

        for ($i = 0; $i < 10; $i++) {
            /**
             * @var BlogPost $blogPost
             */
            $blogPost = new BlogPost();
            $blogPost->setTitle($this->faker->realText(30));
            $blogPost->setPublished($this->faker->dateTimeThisYear);
            $blogPost->setContent($this->faker->realText());
            $blogPost->setAuthor($user);
            $blogPost->setSlug($this->faker->slug);

            $this->setReference("blog_post_$i", $blogPost);

            $manager->persist($blogPost);
        }

        $manager->flush();
    }

    public function loadComment(ObjectManager $manager)
    {
        /**
         * @var User $user
         */
        $user = $this->getReference('user_admin');

        for ($i = 0; $i < 10; $i++) {
            for($j = 0; $j < rand(1, 10); $j++) {
                /**
                 * @var Comment $comment
                 */
                $comment = new Comment();
                $comment->setContent($this->faker->realText());
                $comment->setPublished($this->faker->dateTimeThisYear);
                $comment->setAuthor($user);

                $manager->persist($comment);
            }
        }

        $manager->flush();
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
