<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route("/search/posts", name: "search_posts", methods: ["GET"])]
    public function search_posts() : Response
    {
        return $this->render("search/posts.html.twig");
    }

    #[Route("/search/users", name: "search_users", methods: ["GET"])]
    public function search_users() : Response
    {
        return $this->render("search/users.html.twig");
    }
}
