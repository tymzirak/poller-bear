<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route("/", name: "home", methods: ["GET"])]
    public function index() : Response
    {
        return $this->render("home/index.html.twig");
    }

    #[Route("/settings", name: "settings", methods: ["GET"])]
    public function settings() : Response
    {
        return $this->render("home/settings.html.twig");
    }
}
