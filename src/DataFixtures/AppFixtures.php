<?php

namespace App\DataFixtures;

use App\Entity\Groupe;
use App\Entity\Message;
use App\Entity\User;
use DateTime;
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
    {
        //Generate groupes & users & messages
        for ($i = 0; $i < 25; $i++) {
            $groupe = new Groupe;
            $groupe->setPicture('default-groupe.jpg');
            $groupe->setName('groupe' . $i);

            $user = new User;
            $user->setUsername('user' . $i);
            $user->setPassword(
                $this->encoder->encodePassword(
                    $user,
                    'Ynov2020'
                )
            );
            $user->setPicture('default-user.jpg');
            $user->setEmail('user' . $i . '@gmail.com');
            $manager->persist($user);
            $groupe->addUser($user);
            $groupe->setUsersP($user);

            $message = new Message;
            $message->setGroupe($groupe);
            $message->setUser($user);
            $message->setContent('Salut');
            $message->setDate(new DateTime('now'));
            $message->setState(0);
            $manager->persist($message);

            $groupe->setDate(new DateTime('now'));

            for ($y = $i + 25; $y < $i + 26; $y++) {
                $user2 = new User;
                $user2->setUsername('user' . $y);
                $user2->setPassword(
                    $this->encoder->encodePassword(
                        $user2,
                        'Ynov2020'
                    )
                );
                $user2->setPicture('default-user.jpg');
                $user2->setEmail('user' . $y . '@gmail.com');
                $manager->persist($user2);
                $groupe->addUser($user2);

                $message2 = new Message;
                $message2->setGroupe($groupe);
                $message2->setUser($user2);
                $message2->setContent('Salut');
                $message2->setDate(new DateTime('now'));
                $message2->setState(0);
                $manager->persist($message2);
            }
            $manager->persist($groupe);
        }
        $manager->flush();
    }
}
