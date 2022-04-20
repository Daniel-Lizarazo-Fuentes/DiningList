<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class productController extends AbstractController
{
    #[Route('/product/new')]
    public function showForm(Environment $twig, Request $request, EntityManagerInterface $entityManager)
    {
        $product = new Product();
        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($product);
            $entityManager->flush();

            return new Response('Created new product'.$product->getId());
        }

        return new Response($twig->render('forms/show_product.html.twig',[

            'product_form'=> $form->createView()
        ]));
    }
}