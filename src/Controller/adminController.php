<?php

namespace App\Controller;

use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class adminController extends AbstractController
{
    #[Route("/admin")]
    public function adminPage(): Response
    {

    }
}