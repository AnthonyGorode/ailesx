<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\StatsAilesx;
use App\Repository\UserRepository;
use App\Repository\FlightRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="admin_dashboard_index")
     */
    public function index(ObjectManager $manager, StatsAilesx $statsService, FlightRepository $repoFlight, UserRepository $repoUser)
    {
        $stats = $statsService->getStats();

        $bestDestinations = $statsService->getDestinationStats('DESC');
        $worstDestinations = $statsService->getDestinationStats('ASC');

        $flights = $repoFlight->flightToday();

        $online = count($repoUser->findBy(['connected' => 1 ]));

        $users = $this->getDoctrine()->getManager()->getRepository(User::class)->getActive();

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stats,
            'bestDestinations' => $bestDestinations,
            'worstDestinations' => $worstDestinations,
            'flights' => $flights,
            'online' => $online
        ]);
    }
}
