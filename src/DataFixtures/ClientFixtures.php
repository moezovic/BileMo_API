<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Client;
use App\Entity\User;
use Faker;
use Faker\Generator;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ClientFixtures extends Fixture implements DependentFixtureInterface
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
                $user->setPhoneNumber($this->randomPhoneNumber($faker));
                $user->setAddress($faker->streetAddress );
                $user->setClient($client);
                $user->addPhoneChoice($this->getReference(ProductFixtures::PRODUCT_REFERENCE));
                $manager->persist($user);
            }
            $manager->persist($client);
        }

        $manager->flush();
    }
   // Generates france compatible phone number
   /** 
    * @param Generator $faker
    *
    * @return string
    */
   public static function randomPhoneNumber(Generator $faker)
   {
       $phone = '0'; // May be replaced by "+33"
       $phone .= $faker->numberBetween(1, 6); // 08 / 09 / 07 have special rules
       $phone .= $faker->regexify('[1-5]{8}');

       return $phone;
   }

   public function getDependencies()
    {
        return array(
            ProductFixtures::class,
        );
    }
}
