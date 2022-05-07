<?php

namespace App\Controller;


use App\Entity\Cart;
use App\Entity\Category;
use App\Entity\Product;

use Doctrine\Persistence\ManagerRegistry;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class adminController extends AbstractController
{


    #[Route("/admin")]
    public function adminPage(ManagerRegistry $doctrine)
    {

        $repository = $doctrine->getRepository(Cart::class);
        $carts = $repository->findAll();

        return $this->render('dining/admin.html.twig', [
            'carts' => $carts
        ]);
    }

    #[Route('/admin/confirmall')]
    public function confirmAll(ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $repository = $doctrine->getRepository(Cart::class);
        $carts = $repository->findAll();

        foreach ($carts as $cart) {
            if ($cart->getOrders()) {
                foreach ($cart->getOrders() as $order) {
                    if ($order->getStatus() === "Open") {
                        $order->setStatus("Paid");
                    }
                }
            }
        }

        $entityManager->flush();
        return new Response(null, 204);
    }

    #[Route("/pdf")]
    public function generatePdf(Pdf $snappy): PdfResponse
    {
        $html = $this->renderView('dining/homepage.html.twig', []);
        $snappy->setOption('enable-local-file-access', true);
        ini_set('max_execution_time', 300);

        return new PdfResponse(
            $snappy->getOutputFromHtml($html), 'file.pdf'
        );
    }
}