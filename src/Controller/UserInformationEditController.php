<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\UserInformation\UserInformationEditService;
use App\DTO\UserInformation\UserInformationEditRequestDTO;

class UserInformationEditController extends AbstractController
{
    private UserInformationEditService $userInformationEditService;

    public function __construct(UserInformationEditService $userInformationEditService) 
    {
        $this->userInformationEditService = $userInformationEditService;
    }

    #[Route("/user/edit/info", name: "user_edit_info", methods: ["PUT", "PATCH"])]
    public function userInformationEditService(
        UserInformationEditRequestDTO $userInformationEditRequest
    ): Response {
        $this->userInformationEditService->editUserInformation(
            $userInformationEditRequest,
            $this->getUser()->getUserInformation()
        );
        
        return $this->redirect(
            $this->generateUrl("settings", ["success" => "User information edited successfully."])
        );
    }
}
