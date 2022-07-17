<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\User\UserEditPasswordService;
use App\DTO\User\UserEditPasswordRequestDTO;

class UserEditPasswordController extends AbstractController
{
    private UserEditPasswordService $userEditPasswordService;

    public function __construct(UserEditPasswordService $userEditPasswordService) 
    {
        $this->userEditPasswordService = $userEditPasswordService;
    }

    #[Route("/user/edit/password", name: "user_edit_password", methods: ["PUT", "PATCH"])]
    public function userEditPassword(UserEditPasswordRequestDTO $userEditPasswordRequest): Response
    {
        $this->userEditPasswordService->editUserPassword($userEditPasswordRequest, $this->getUser());

        return $this->redirect(
            $this->generateUrl("settings", ["success" => "User password changed successfully."])
        );
    }
}
