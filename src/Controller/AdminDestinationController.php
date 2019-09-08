<?php

namespace App\Controller;

use App\Entity\Airport;
use App\Service\Paginator;
use App\Service\PaginatorDql;
use App\Repository\AirportRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDestinationController extends AbstractController
{
    /**
     * Permet d'afficher le tableau des aéroports
     * 
     * @Route("/admin/destination/{column?a.id}/{order?ASC}/{concat?0}/{page<\d+>?1}", name="admin_destination_index")
     */
    public function index($column,$order,$concat,$page, PaginatorDql $pagination, AirportRepository $repoAirport,Request $request)
    {
        // Si il n'y a une recherche effectué je place la recherche dans concat
        if(!empty($request->query->get('concact'))){
            $concat = $request->query->get('concact');
        }
        
        $pagination->setEntityClass(Airport::class)
                   ->setColumn($column)
                   ->setOrder($order)
                   ->setConcat($concat)
                   ->setLimit(5)
                   ->setPage($page);

        return $this->render('admin/destination/index.html.twig', [
            'pagination' => $pagination,
            'concatenation' => $concat
        ]);
    }
}
