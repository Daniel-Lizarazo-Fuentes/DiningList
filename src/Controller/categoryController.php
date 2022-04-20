<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class categoryController extends AbstractController
{
    #[Route('/category/new')]
    public function showForm(Environment $twig, Request $request, EntityManagerInterface $entityManager)
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($category);
            $entityManager->flush();

            return new Response('Created new category'.$category->getId());
        }

        return new Response($twig->render('forms/show_category.html.twig',[
            'category_form'=> $form->createView()
        ]));
    }
}