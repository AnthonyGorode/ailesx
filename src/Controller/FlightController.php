<?php

namespace App\Controller;

use DateTime;
use Faker\Factory;
use App\Entity\Seat;
use App\Entity\Flight;
use App\Entity\Booking;
use App\Entity\FlightSeat;
use App\Repository\SeatRepository;
use App\Repository\FlightRepository;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class FlightController extends AbstractController
{
    /**
     * 
     * Permet d'afficher tous les vols disponibles
     * 
     * @Route("/flight", name="flight_index")
     * @IsGranted("ROLE_USER")
     * 
     */
    public function index(FlightRepository $repo)
    {
        $flights = $repo->findAll();

        return $this->render('flight/index.html.twig', [
            'flights' => $flights
        ]);
    }

    /**
     * Permet d'afficher d'afficher les informations pour un vol
     * Cette fonction sert pour l'affichage et la réservation
     * 
     * @Route("/flight/{id}", name="flight_booking")
     * @IsGranted("ROLE_USER")
     * 
     */
    public function book(Flight $flight,SeatRepository $repoSeat, BookingRepository $booking, Request $request,ObjectManager $manager)
    {
        $user = $this->getUser()->getId();
        $oneFlight = $flight->getId();
        $everBooking = 0;

        //Récupère le siège choisi par l'utilisateur dans le select
        $oneSeat = $request->get('oneSeat');

        // Récupère tous les vols de l'utilsateur connecté
        $userBookings = $booking->findBy(
            ['booker' => $user]
        );
        // Vérifie si l'utilisateur à déjà réservé sur ce vol
        for($i=0;$i<count($userBookings);$i++){
            // vol de l'utilisateur
            $userFlight = $userBookings[$i]->getFlight()->getId();

            // si le vol de l'utilisateur est égale au vol 
            // où il accède par le site, alors je bloque l'accès
            if($userFlight == $oneFlight){ 
                $everBooking = 1;
                $i = count($userBookings);
            }

        }

        // Condition si l'utilisateur à choisi un siège
        if($oneSeat != ""){

            // récupère l'id du siège choisi par l'utilisateur
            $seat = $repoSeat->findOneBy(
                ['numSeat' => $oneSeat]
            )->getId();

            // findOneBooking est une requête paramétré dans BookingRepository
            // Permet de récupérer l'id de la réservation (booking)
            $treatBooking = $this->getDoctrine()->getRepository(Booking::class)->findOneBooking($oneFlight,$seat);
            $idBooking = $treatBooking[0]['id'];

            $dateObject = new \DateTime();
            $dateToday = $dateObject->format('Y-m-d H:i:s');

            // updateBooking est une requête paramétré dans BookingRepository
            // Permet de mettre à jour la réservation en y rajoutant l'id de l'utilisateur et la date du jour de la réservation
            $setBooking = $this->getDoctrine()->getRepository(Booking::class)->updateBooking($idBooking,$user,$dateToday);

            return $this->redirectToRoute('booking_index');
        }

        $activeSeat = 0;

        // findAllSeatsForOneFlight est une requête paramétré dans BookingRepository
        // Permet de récupérer tous les sièges concernant un vol
        $seats = $this->getDoctrine()->getRepository(Booking::class)->findAllSeatsForOneFlight($oneFlight,$activeSeat);

        return $this->render('flight/oneFlight.html.twig', [
            'flight' => $flight,
            'seats' => $seats,
            'everBooking' => $everBooking
        ]);
    }

}
