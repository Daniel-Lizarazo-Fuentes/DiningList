<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DiningController extends AbstractController
{


    #[Route("/")]
    public function dashBoard(): Response
    {
        $categories = [
            ['categoryName' => "Fish", 'description' => "Delicious fish"],
            ['categoryName' => "Soup", 'description' => "Delicious Soup"],
            ['categoryName' => "Sandwich", 'description' => "Delicious Sandwich"],

        ];

        return $this->render('dining/dashboard.html.twig', [
            'categories' => $categories
        ]);
    }
}