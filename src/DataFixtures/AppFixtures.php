<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Jet;
use App\Entity\City;
use App\Entity\Role;
use App\Entity\Seat;
use App\Entity\User;
use App\Entity\Flight;
use App\Entity\Airport;
use App\Entity\Booking;
use App\Entity\FlightSeat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $lastActivity = $faker->dateTimeBetween('-6 months');

         $adminUser = new User();
         $adminUser->setFirstName('Anthony')
                   ->setLastName('Gorode')
                   ->setEmail('anthony.gorode@gmail.com')
                   ->setHash($this->encoder->encodePassword($adminUser,'password'))
                   ->setPicture('https://media-exp1.licdn.com/dms/image/C5603AQFF26ouGKK4eA/profile-displayphoto-shrink_200_200/0?e=1586995200&v=beta&t=n9zZOJ_WFKlMaftKCF8nbcIWYfub0VC3WRf64cEw2Kc')
                   ->setLastActivity($lastActivity)
                   ->setConnected(false)
                   ->addUserRole($adminRole);
        $manager->persist($adminUser);


        //Nous gérons les utilisateurs
        $users=[];
        $genres=['male','female'];

        for($i=1; $i <=10; $i++){
            $user = new User();

            $genre = $faker->randomElement($genres);
            $lastActivityUser = $faker->dateTimeBetween('-6 months');

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1,99) . '.jpg';

            // condition ternaire
            $picture .=($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            // condition classique correspondante
            // if($genre == 'male') $picture = $picture . 'men/' . $picutreId;
            // else $picture = $picture . 'women/' . $pictureId;

            $hash = $this->encoder->encodePassword($user,'password');

            $user->setFirstName($faker->firstname($genre))
                 ->setLastName($faker->lastname)
                 ->setEmail($faker->email)
                 ->setHash($hash)
                 ->setPicture($picture)
                 ->setLastActivity($lastActivityUser)
                 ->setConnected(false);

            $manager->persist($user);
            $users[] = $user;
        }

         // Nous gérons les villes
         for ($j=1;$j<=8;$j++){
            $city = new City();

            $city->setNameCity($faker->city);

            $manager->persist($city);
            $tabCity[] = $city;
        }

        // Nous gérons les aéroports
        for ($j=1;$j<=12;$j++){
            $airport = new Airport();

            $oneCity = $tabCity[mt_rand(0, count($tabCity) -1)];

            $airport->setNameAirport($faker->streetName)
                    ->setCity($oneCity);

            $manager->persist($airport);
            $tabAirport[] = $airport;
        }

        // Nous gérons les jets
        for ($i=1;$i<=2;$i++){

            $tab = ["","PC-12NG","PC-13NG"];
            $typeJet = $tab[$i];

            $jet = new Jet();

            $user = $users[mt_rand(0,count($users) -1)];

            $jet->setTypeJet($typeJet);

            // Nous gérons les sièges d'un jet
            for ($j=1;$j<=8;$j++){
                $seat = new Seat();

                $seat->setNumSeat("ST-" . $faker->postcode)
                      ->setJet($jet);

                $manager->persist($seat);
            }


            // // Gestion des réservations
            // for($j = 1; $j <= mt_rand(0, 10); $j++){
            //     $booking = new Booking();

            //     $createdAt = $faker->dateTimeBetween('-6 months');

            //     $booker = $users[mt_rand(0, count($users) -1)];

            //     $booking->setBooker($booker)
            //             ->setSeat($seat)
            //             ->setNumBooking("AX-" . $faker->postcode)
            //             ->setCreatedAt($createdAt);
                
            //     $manager->persist($booking);
            // }

            $manager->persist($jet);
            $tabJet[] = $jet;
        }

         // Nous gérons les vols
         $compteur=0;
        for($j = 1; $j <= 10; $j++){
            $flight = new Flight();

            // $oneJet = $tabJet[mt_rand(0, count($tabJet) -1)];

            $startDate = $faker->dateTimeBetween('now','3 months');

            //Gestion de la date de fin
            $duration = mt_rand(1,3);
            $endDate = (clone $startDate)->modify("+$duration hours");

            $oneAirportDeparture = $tabAirport[mt_rand(0, count($tabAirport) -1)];
            $oneAirportArrival = $tabAirport[mt_rand(0, count($tabAirport) -1)];


            $flight->setNumFlight("AX-".$faker->postcode)
                   ->setAirportDeparture($oneAirportDeparture)
                   ->setAirportArrival($oneAirportArrival)
                   ->setHourDeparture($startDate)
                   ->setHourArrival($endDate);
            
            $manager->persist($flight);
            
        }

        $manager->flush();
    }
}
