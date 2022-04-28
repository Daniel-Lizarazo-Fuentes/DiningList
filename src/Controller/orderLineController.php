<?php

namespace App\Controller;


use App\Entity\OrderLine;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


class orderLineController extends AbstractController
{


    #[Route("orderline/delete/{id}")]
    public function delete(ManagerRegistry $doctrine, $id, EntityManagerInterface $entityManager)
    {
        $orderLine = $doctrine->getRepository(OrderLine::class)->find($id);
        if ($orderLine) {
            $entityManager->remove($orderLine);
            $entityManager->flush();
        }
        return new Response(null, 204);
    }


}