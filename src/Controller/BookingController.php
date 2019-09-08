<?php

namespace App\Controller;

use App\Entity\Seat;
use App\Entity\Flight;
use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class BookingController extends AbstractController
{
    /**
     * Permet à l'utilisateur de consulter sa réservation
     * 
     * @Route("/bookFlight", name="booking_index")
     * @IsGranted("ROLE_USER")
     *
     * @return void
     */
    public function bookingUser(BookingRepository $booking){
        $user = $this->getUser()->getId();
        $issetBooking=0;

        $userBookings = $booking->findBy(
            ['booker' => $user]
        );
        if(!isset($userBookings[0])){
            $issetBooking = 1;
        }
        
        dump($userBookings);

        return $this->render('booking/bookFlight.html.twig',[
            'userBookings' => $userBookings,
            'issetBooking' => $issetBooking
        ]);
        
    }
}
