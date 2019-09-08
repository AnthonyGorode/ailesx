<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Service\Paginator;
use App\Service\PaginatorDql;
use App\Repository\FlightRepository;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings/{column?b.id}/{order?ASC}/{concat?0}/{page<\d+>?1}", name="admin_bookings_index")
     */
    public function index(BookingRepository $repoBooking, $column, $order, $concat, $page, PaginatorDql $pagination, Request $request)
    {
        // Si il y a une recherche effectué je place la recherche dans la variable concat
        if(!empty($request->query->get('concact'))){
            $concat = $request->query->get('concact');
        }

        $pagination->setEntityClass(Booking::class)
                   ->setColumn($column)
                   ->setOrder($order)
                   ->setConcat($concat)
                   ->setPage($page);

        return $this->render('admin/booking/index.html.twig', [
            'pagination' => $pagination,
            'concatenation' => $concat
        ]);
    }
}
