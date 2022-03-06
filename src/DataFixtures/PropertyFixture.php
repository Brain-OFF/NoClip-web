<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
class PropertyFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for($i = 0 ; $i < 100 ; $i++)
        {
            $reservation = new reservation();
            $reservation
                ->setTempsend($faker->dateTime($min = 'today', $timezone = null))
                ->setTempsstart($faker->dateTime($min = 'today', $timezone = null))
                ->setDispo(1)
            ;

            $manager->persist($reservation);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
