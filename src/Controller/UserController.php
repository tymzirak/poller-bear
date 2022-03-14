<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use App\Service\UserEditService;
use App\Service\UserDeleteService;
use App\DTO\UserEditRequestDTO;
use App\DTO\UserDeleteRequestDTO;

class UserController extends AbstractController
{
    private UserEditService $userEditService;
    private UserDeleteService $userDeleteService;

    public function __construct(UserEditService $userEditService, UserDeleteService $userDeleteService) {
        $this->userEditService = $userEditService;
        $this->userDeleteService = $userDeleteService;
    }

    #[Route("/user/posts", name: "user_posts", methods: ["GET"])]
    public function userPosts() : Response
    {
        return $this->render("user/posts.html.twig");
    }

    #[Route("/user/comments", name: "user_comments", methods: ["GET"])]
    public function userComments() : Response
    {
        return $this->render("user/comments.html.twig");
    }

    #[Route("/user/edit", name: "user_edit", methods: ["PUT", "PATCH"])]
    public function userEdit(UserEditRequestDTO $request) : Response
    {
        try {
            $this->userEditService->attemptToEditUser($request, $this->getUser());
            return $this->redirect($this->generateUrl("settings"));
        } catch (BadRequestHttpException $error) {
            return new Response($error->getMessage());
        }
    }

    #[Route("/user/delete", name: "user_delete", methods: ["DELETE"])]
    public function userDelete(UserDeleteRequestDTO $request) : Response
    {
        try {
            $this->userDeleteService->deleteUser($this->getUser());
            return $this->redirect($this->generateUrl("logout"));
        } catch (\Exception $error) {
            return new Response("Input error.");
        }
    }
}
