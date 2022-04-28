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

        if($order->getStatus()==="Open"){
            return $this->render('dining/show_order.html.twig', ['order' => $order]);
        }
        else{
            return new Response("The order with ID ".$order->getId()." has been paid!");
        }

    }

    #[Route("/order/confirm/{id}")]
    public function confirmOrder(ManagerRegistry $doctrine, int $id): Response{
        $entityManager = $doctrine->getManager();
        $order = $entityManager->getRepository(Order::class)->find($id);
        if(!$order){
            throw $this->createNotFoundException(
                'No order found for id '.$id
            );
        }

        $order->setStatus("Paid");
        $entityManager->flush();

        return new Response(null, 204);
    }

}