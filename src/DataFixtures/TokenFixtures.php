<?php

namespace App\DataFixtures;

use App\Entity\Token;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TokenFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $token = new Token();
        $token
            ->setName('App1')
            ->setUsername('app1')
            ->setPassword($this->encoder->encodePassword($token, 'pass123_app1'))
        ;

        $manager->persist($token);
        $manager->flush();
    }
}
