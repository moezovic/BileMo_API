<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Client;
use App\Entity\User;
use Faker;

class ClientFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for($i = 0; $i<3; $i++){
            $client = new Client();
            $client->setEmail("client_".$i."@gmail.com");
            $client->setPassword($this->passwordEncoder->encodePassword($client, "client_".$i));
            for($ii = 0; $ii<10; $ii++)
            {
                $user = new User();
                $user->setFirstName($faker->firstName);
                $user->setLastName($faker->lastName);
                $user->setPhoneNumber("0000000000");
                $user->setAddress($faker->streetAddress );
                $user->setClient($client);
                $manager->persist($user);
            }
            $manager->persist($client);
        }

        $manager->flush();
    }
}
