<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\Email\EmailSendVerificationService;
use App\Service\User\UserDeleteService;
use App\DTO\Email\EmailVerifyRequestDTO;
use App\DTO\User\UserDeleteRequestDTO;

class UserDeleteController extends AbstractController
{
    private EmailSendVerificationService $emailSendVerificationService;
    private UserDeleteService $userDeleteService;

    public function __construct(
        EmailSendVerificationService $emailSendVerificationService,
        UserDeleteService $userDeleteService
    ) {
        $this->emailSendVerificationService = $emailSendVerificationService;
        $this->userDeleteService = $userDeleteService;
    }

    #[Route("/user/delete", name: "user_delete", methods: ["DELETE"])]
    public function userDelete(UserDeleteRequestDTO $userDeleteRequest): Response
    {
        $this->emailSendVerificationService->sendEmailVerification($this->getUser(), "user_delete_verify");

        return $this->redirect(
            $this->generateUrl("settings", ["success" => "Email verification sent successfully."])
        );
    }

    #[Route("/user/delete/verify", name: "user_delete_verify", methods: ["GET"])]
    public function userDeleteVerify(EmailVerifyRequestDTO $emailVerifyRequest): Response
    {
        $this->userDeleteService->deleteUser($emailVerifyRequest);

        return $this->redirect($this->generateUrl("login", ["success" => "User deleted successfully."]));
    }
}
