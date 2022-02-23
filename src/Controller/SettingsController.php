<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SettingsController extends AbstractController
{
    #[Route("/settings", name: "settings", methods: ["GET"])]
    public function settings() : Response
    {
        return $this->render("home/settings.html.twig");
    }
}
