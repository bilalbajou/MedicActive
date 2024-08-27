<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixture extends Fixture
{

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
        // ...
    }


    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($faker->unique()->safeEmail);
            $user->setRoles(['ROLE_USER']);

            $user->setUsername($faker->userName);
            
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                'password' // Vous pouvez générer un mot de passe aléatoire avec Faker si nécessaire
            );
            $user->setPassword($hashedPassword);

            $manager->persist($user);
        }

        $manager->flush();


    }
}
