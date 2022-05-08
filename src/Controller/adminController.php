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
    public function adminPage(ManagerRegistry $doctrine): Response
    {
        $arrays = $this->getAdminArrays($doctrine);
        return $this->render('dining/admin.html.twig', [
            'productPrices' => $arrays[0],
            'totalProducts' => $arrays[1],
            'totalPerUser' => $arrays[2]
        ]);

    }

    #[Route('/admin/confirmall')]
    public function confirmAll(ManagerRegistry $doctrine, Pdf $snappy)
    {
        $arrays = $this->getAdminArrays($doctrine);
        $html = $this->render('dining/open_order_tables.html.twig', [
            'productPrices' => $arrays[0],
            'totalProducts' => $arrays[1],
            'totalPerUser' => $arrays[2]
        ])->getContent();
        $snappy->setOption('enable-local-file-access', true);
        ini_set('max_execution_time', 300);


//        $entityManager = $doctrine->getManager();
//        $repository = $doctrine->getRepository(Cart::class);
//        $carts = $repository->findAll();
//
//        foreach ($carts as $cart) {
//            if ($cart->getOrders()) {
//                foreach ($cart->getOrders() as $order) {
//                    if ($order->getStatus() === "Open") {
//                        $order->setStatus("Paid");
//                    }
//                }
//            }
//        }
//
//        $entityManager->flush();

        return new PdfResponse(
            $snappy->getOutputFromHtml($html), 'file.pdf'
        );
    }

    public function getAdminArrays(ManagerRegistry $doctrine): array
    {
        $repository = $doctrine->getRepository(Cart::class);
        $carts = $repository->findAll();

        $totalProducts = [];
        $totalPerUser = [];

        foreach ($carts as $cart) {
            if ($cart->getOrders()) {
                foreach ($cart->getOrders() as $order) {
                    if ($order->getStatus() === "Open") {
                        foreach ($order->getOrderLines() as $orderLine) {

                            $key = $orderLine->getProduct()->getName();
                            $orderLineQuantity = $orderLine->getQuantity();

                            if (array_key_exists($key, $totalProducts)) {
                                $totalProducts[$key] = $orderLineQuantity + $totalProducts[$key];
                            } else {
                                $totalProducts[$key] = $orderLineQuantity;
                            }
                            $userKey = $cart->getUID()->getEmail();
                            if (array_key_exists($userKey, $totalPerUser)) {
                                if (isset($totalPerUser[$userKey][$key])) {
                                    $totalPerUser[$userKey][$key] = $orderLineQuantity + $totalPerUser[$userKey][$key];
                                } else {
                                    $totalPerUser[$userKey] += [$key => $orderLineQuantity];
                                }
                            } else {
                                $totalPerUser[$userKey] = [$key => $orderLineQuantity];
                            }
                        }
                    }
                }
            }
        }

        $repository = $doctrine->getRepository(Product::class);
        $products = $repository->findAll();

        $productPrices = [];
        foreach ($products as $product) {
            $productPrices[$product->getName()] = $product->getPrice();
        }


        return [$productPrices, $totalProducts, $totalPerUser];
    }


}