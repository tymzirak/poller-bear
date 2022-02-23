<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\UserEditService;
use App\Service\UserDeleteService;

class UserController extends AbstractController
{
    private UserEditService $userEditService;
    private UserDeleteService $userDeleteService;

    public function __construct(
        UserEditService $userEditService,
        UserDeleteService $userDeleteService
    ) {
        $this->userEditService = $userEditService;
        $this->userDeleteService = $userDeleteService;
    }

    #[Route("/user/posts", name: "user_posts", methods: ["GET"])]
    public function user_posts() : Response
    {
        return $this->render("user/posts.html.twig");
    }

    #[Route("/user/comments", name: "user_comments", methods: ["GET"])]
    public function user_comments() : Response
    {
        return $this->render("user/comments.html.twig");
    }

    #[Route("/user/edit", name: "user_edit", methods: ["PUT", "PATCH"])]
    public function user_edit(Request $request) : Response
    {
        $requestData = $request->toArray();

        if (!$error = $this->userEditService->getLastUserActionViolation($requestData, $this->getUser())) {
            return $this->redirect($this->generateUrl("settings"));
        }

        return new Response($error->getMessage());
    }

    #[Route("/user/delete", name: "user_delete", methods: ["DELETE"])]
    public function user_delete(Request $request) : Response
    {
        $requestData = $request->toArray();

        if (!$error = $this->userDeleteService->getLastUserActionViolation($requestData, $this->getUser())) {
            return $this->redirect($this->generateUrl("logout"));
        }

        return new Response($error->getMessage());
    }
}
