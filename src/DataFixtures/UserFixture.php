<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Cake\Chronos\Date;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('tkeskic95@gmail.com');
        $user->setFirstName('Tomislav');
        $user->setLastName('Keškić');
        $user->setPhone('+385919262180');
        $user->setDateOfBirth(new Date('28-07-1995'));
        $user->setSex('male');
        $user->setHeight(180);
        $user->setWeight(80);
        $user->setPassword($this->hasher->hashPassword($user, '123'));

        $manager->persist($user);
        $manager->flush();
    }
}