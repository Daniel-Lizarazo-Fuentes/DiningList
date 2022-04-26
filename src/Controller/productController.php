<?php

namespace App\Controller;

use App\Entity\OrderLine;
use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\Product;
use App\Form\AddOrderLineType;
use App\Form\ProductFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Symfony\Component\Security\Core\Security;

class productController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/product/new')]
    public function showForm(Environment $twig, Request $request, EntityManagerInterface $entityManager)
    {
        $product = new Product();
        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();

            return new Response('Created new product' . $product->getId());
        }

        return new Response($twig->render('forms/show_product.html.twig', [

            'product_form' => $form->createView()
        ]));
    }

    #[Route("/product/{id}")]
    public function showProduct(ManagerRegistry $doctrine, int $id, Environment $twig, Request $request, EntityManagerInterface $entityManager)
    {
        $orderLine = new OrderLine();
        $form = $this->createForm(AddOrderLineType::class, $orderLine);
        $form->handleRequest($request);

        $product = $doctrine->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $orderLine->setProduct($product);

            $carts = $doctrine->getRepository(Cart::class)->findAll();
            $user = $this->security->getUser();

            $cart = new Cart();
            $cart->setUID($user);

            foreach ($carts as $tempCart) {
                if ($tempCart->getUID() === $user) {
                    $cart = $tempCart;
                    break;
                }
            }

            $order = new Order();
            $order->setCart($cart)->setStatus("Open");

            if ($cart->getOrders()) {
                $orders = $cart->getOrders();
                foreach ($orders as $tempOrder) {
                    if ($tempOrder->getStatus() == "Open") {
                        $order = $tempOrder;
                        break;
                    }
                }
            }

            $order->addOrderLine($orderLine);
            $cart->addOrder($order);

            $entityManager->persist($orderLine);
            $entityManager->persist($order);
            $entityManager->persist($cart);

            $entityManager->flush();


            return new Response('Added new item to your order');
        }

        return new Response($twig->render('forms/show_product.html.twig', [

            'product_form' => $form->createView()
        ]));


    }
}