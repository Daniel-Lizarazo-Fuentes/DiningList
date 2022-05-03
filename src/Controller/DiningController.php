<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class DiningController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route("/", name: 'homepage')]
    public function homePage(): Response
    {
        return $this->render('dining/homepage.html.twig', []);
    }

    #[Route("/dashboard/", name: 'dashboard')]
    public function dashBoard(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Category::class);
        // look for *all* Category objects
        $categories = $repository->findAll();

        $repository = $doctrine->getRepository(Product::class);
        // look for *all* Product objects
        $products = $repository->findAll();

        $user = $this->security->getUser();
        $repository = $doctrine->getRepository(Cart::class);
        $cart = $repository->findOneBy(array('uID' => $user));

        return $this->render('dining/dashboard.html.twig', [
            'categories' => $categories,
            'products' => $products,
            'cart' => $cart
        ]);
    }



}