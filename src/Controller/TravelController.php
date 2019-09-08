<?php

namespace App\Controller;

use App\Repository\CityRepository;
use App\Repository\FlightRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TravelController extends AbstractController
{

    /**
     * Cette route affiche l'accueil quand on est connecté
     * Elle gère le formulaire de recherche de vol
     * 
     * @Route("/", name="home")
     */
    public function home(CityRepository $repoCity, FlightRepository $repoFlight, Request $request){
        $user = $this->getUser();
        $flights = $repoFlight->findAll();
        $departure = $request->get('Departure');
        $arrival = $request->get('Arrival');
        $dateDeparture = $request->get('dateDeparture');
        $submit = $request->get('search_flights');
        
        
        $cities = $repoCity->findAll();
        $flightTarget = [];
        $flagFound = 0;
        $j = 0;
        if( $departure != "De :" && $arrival != "À :" && $dateDeparture != ""){
            for($i=0; $i<count($flights); $i++){
                $departureFlight = $flights[$i]->getAirportDeparture()->getCity()->getNameCity();
                $arrivalFlight = $flights[$i]->getAirportArrival()->getCity()->getNameCity();

                $databaseDate = date_format($flights[$i]->getHourDeparture(),'d/m/Y'); 
                if($departure == $departureFlight && $arrival == $arrivalFlight && $dateDeparture == $databaseDate){
                    $flightTarget[$j] = $flights[$i];
                    $i= count($flights);
                    $flagFound = 1;
                }
            }
            if($flagFound == 1){
                return $this->render('flight/index.html.twig',[
                    'flights' => $flightTarget
                ]);
            }else{
                return $this->render('travel/home.html.twig',[
                    'controller_page' => 'home',
                    'cities' => $cities,
                    'not_found' => 'aucun vol ne correspond à votre recherche.'
                ]);
            }
        }else if( $submit == 'RECHERCHER'){
            if($departure == "De :" || $arrival == "À :" || $dateDeparture == ""){
                return $this->render('travel/home.html.twig',[
                    'controller_page' => 'home',
                    'cities' => $cities,
                    'not_found' => 'Vous n\'avez pas rempli tous les champs.'
                ]);
            }
        }else{
            return $this->render('travel/home.html.twig',[
                'controller_page' => 'home',
                'cities' => $cities
            ]);
        }

    }

    /**
     * 
     * Ces routes paramètrées sont accessibles sans authentification
     * Elles sont situées dans le dossier 'travel'
     * 
     * @Route("/home/{name}", name="page")
     */
    public function index($name)
    {
        return $this->render('travel/'.$name.'.html.twig', [
            'controller_page' => $name,
        ]);
    }

    // /**
    //  * @Route("/login", name="login")
    //  */
    // public function login(){
    //     return $this->render('travel/login.html.twig',[
    //         'controller_page' => 'login',
    //     ]);
    // }

    //  /**
    //  * @Route("/inscription", name="inscription")
    //  */
    // public function inscription(){
    //     return $this->render('travel/inscription.html.twig',[
    //         'controller_page' => 'inscription',
    //     ]);
    // }

    //  /**
    //  * @Route("/tarifs", name="tarifs")
    //  */
    // public function tarifs(){
    //     return $this->render('travel/tarifs.html.twig',[
    //         'controller_page' => 'tarifs',
    //     ]);
    // }

    //  /**
    //  * @Route("/aircraft", name="aircraft")
    //  */
    // public function aircraft(){
    //     return $this->render('travel/aircraft.html.twig',[
    //         'controller_page' => 'aircraft',
    //     ]);
    // }

    //  /**
    //  * @Route("/invite", name="invite")
    //  */
    // public function invite(){
    //     return $this->render('travel/invite.html.twig',[
    //         'controller_page' => 'invite',
    //     ]);
    // }

    //  /**
    //  * @Route("/destination", name="destination")
    //  */
    // public function destination(){
    //     return $this->render('travel/destination.html.twig',[
    //         'controller_page' => 'destination',
    //     ]);
    // }

    //  /**
    //  * @Route("/report", name="report")
    //  */
    // public function report(){
    //     return $this->render('travel/report.html.twig',[
    //         'controller_page' => 'report',
    //     ]);
    // }

    //  /**
    //  * @Route("/schedule", name="schedule")
    //  */
    // public function schedule(){
    //     return $this->render('travel/schedule.html.twig',[
    //         'controller_page' => 'schedule',
    //     ]);
    // }

    //  /**
    //  * @Route("/experience", name="experience")
    //  */
    // public function experience(){
    //     return $this->render('travel/experience.html.twig',[
    //         'controller_page' => 'experience',
    //     ]);
    // }

    // /**
    //  * @Route("/animation", name="animation")
    //  */
    // public function animation(){
    //     return $this->render('travel/animation.html.twig',[
    //         'controller_page' => 'animation',
    //     ]);
    // }

    //  /**
    //  * @Route("/oneArticle", name="articleSingle")
    //  */
    // public function oneArticle(){
    //     return $this->render('travel/oneArticle.html.twig',[
    //         'controller_page' => 'oneArticle',
    //     ]);
    // }
}
