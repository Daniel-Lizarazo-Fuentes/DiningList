<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class orderController extends AbstractController
{


    #[Route("/order/{id}")]
    public function showOrder(ManagerRegistry $doctrine, int $id): Response
    {
        $order = $doctrine->getRepository(Order::class)->find($id);

        if (!$order) {
            throw $this->createNotFoundException(
                'No order found for id ' . $id
            );
        }




         return $this->render('dining/show_order.html.twig', ['order' => $order]);
    }


}