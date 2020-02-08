<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {        //Generate users
        for ($i = 0; $i < 50; $i++) {
            $user = new User;
            $user->setUsername('user' . $i);
            $user->setPassword(
                $this->encoder->encodePassword(
                    $user, 'Ynov2020'
                )
            );
            $user->setPicture('default-user.jpg');
            $user->setEmail('user' . $i . '@gmail.com');
            $manager->persist($user);
        }
        $manager->flush();
    }
}
