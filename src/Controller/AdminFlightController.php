<?php

namespace App\Controller;

use App\Entity\Flight;
use App\Entity\Airport;
use App\Entity\Booking;
use App\Form\FlightType;
use App\Service\PaginatorDql;
use App\Repository\JetRepository;
use App\Repository\SeatRepository;
use App\Repository\FlightRepository;
use App\Repository\AirportRepository;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class AdminFlightController extends AbstractController
{
    /**
     * Permet d'ajouter un vol
     * 
     * @Route("/admin/flights/new", name="admin_flights_new")
     *
     * @return void
     */
    public function add(Request $request, AirportRepository $repoAirport, ObjectManager $manager,JetRepository $repoJet,SeatRepository $repoSeat, FlightRepository $repoFlight){
        $flight = new Flight();

        $jets = $repoJet->findAll();

        $airports = $repoAirport->findAll();

        $form = $this->createForm(FlightType::class, $flight);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $departure = $request->get('Departure');
            $arrival = $request->get('Arrival');
            $nameJet = $request->get('jet');

            $arrayDeparture = $repoAirport->findAirport($departure);
            $arrayArrival = $repoAirport->findAirport($arrival);

            $jet = $repoJet->findOneBy(["typeJet" => $nameJet]);
            $seats = $repoSeat->findBy(["jet" => $jet]);

            $airportDeparture = $arrayDeparture[0];
            $airportArrival = $arrayArrival[0];

            $flight->setAirportDeparture($airportDeparture);
            $flight->setAirportArrival($airportArrival);

            $manager->persist($flight);
            $manager->flush();

            $nameFlight = $flight->getNumFlight();
            $flightCreate = $repoFlight->findOneBy(["numFlight" => $nameFlight]);

            foreach($seats as $ligne){
                $booking = new Booking();

                $booking->setSeat($ligne)
                        ->setFlight($flightCreate)
                        ->setActiveSeat(0);

                $manager->persist($booking);
                $manager->flush();
            }
        }

        return $this->render('admin/flight/add.html.twig',[
            'airports' => $airports,
            'form' => $form->createView(),
            'jets' => $jets,
            'controller_page' => 'add'
        ]);

    }

    /**
     * Permet d'editer un vol 
     * 
     * @Route("admin/flights/{id}/edit", name="admin_flights_edit")
     *
     * @param Flight $flight
     * @return Response
     */
    public function edit(Flight $flight,AirportRepository $repoAirport,Request $request,ObjectManager $manager, FlightRepository $repoFlight, BookingRepository $repoBooking){
        $airports = $repoAirport->findAll();
        $bookings = $repoBooking->findAllSeatsForOneFlight($flight,1);

        $form = $this->createForm(FlightType::class, $flight);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $departure = $request->get('Departure');
            $arrival = $request->get('Arrival');

            $idDeparture = $repoAirport->findIdAirport($departure);
            $idArrival = $repoAirport->findIdAirport($arrival);
            dump($request);
            dump($request->get('hourArrival'));
            
            $repoFlight->updateAirportFlight($idDeparture[0]['id'],$idArrival[0]['id'],$flight->getId());
            
            $manager->persist($flight);
            $manager->flush();

            $this->addFlash(
                'success',
                'les modifications sur le vol<strong>'.$flight->getNumFlight().'</strong> ont bien été enregistrées.'
            );

            return $this->redirectToRoute('admin_flights_index');
        }

        return $this->render('admin/flight/edit.html.twig',[
            'flight' => $flight,
            'airports' => $airports,
            'form' => $form->createView(),
            'bookings' => $bookings,
            'controller_page' => 'edit'
        ]);
    }

    /**
     * @Route("/admin/flights/{column?countBooker}/{order?DESC}/{concat?0}/{page<\d+>?1}", name="admin_flights_index")
     */
    public function index(FlightRepository $repoFlight, BookingRepository $repoBooking, $column, $order, $concat, $page, PaginatorDql $pagination, Request $request)
    {
        // Si il n'y a une recherche effectué je place la recherche dans concat
        if(!empty($request->query->get('concact'))){
            $concat = $request->query->get('concact');
        }

        $pagination->setEntityClass(Flight::class)
                   ->setColumn($column)
                   ->setOrder($order)
                   ->setConcat($concat)
                   ->setLimit(5)
                   ->setPage($page);
        
        return $this->render('admin/flight/index.html.twig', [
            'pagination' => $pagination,
            'concatenation' => $concat
        ]);
    }

}
