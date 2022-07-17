<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\Email\EmailSendVerificationService;
use App\Service\UserInformation\UserInformationEditService;
use App\Service\User\UserEditEmailService;
use App\Service\User\UserSendEditEmailVerificationService;
use App\DTO\Email\EmailVerifyRequestDTO;
use App\DTO\Email\EmailEditRequestDTO;

class UserEditEmailController extends AbstractController
{
    private EmailSendVerificationService $emailSendVerificationService;
    private UserEditEmailService $userEditEmailService;
    private UserSendEditEmailVerificationService $userSendEditEmailVerificationService;

    public function __construct(
        EmailSendVerificationService $emailSendVerificationService,
        UserEditEmailService $userEditEmailService, 
        UserSendEditEmailVerificationService $userSendEditEmailVerificationService,
    ) {
        $this->emailSendVerificationService = $emailSendVerificationService;
        $this->userEditEmailService = $userEditEmailService;
        $this->userSendEditEmailVerificationService = $userSendEditEmailVerificationService;
    }

    #[Route("/user/edit/email", name: "user_edit_email", methods: ["PUT", "PATCH"])]
    public function userEditEmail(EmailEditRequestDTO $emailEditRequest): Response
    {
        $this->userSendEditEmailVerificationService->sendUserEditEmailVerification(
            $emailEditRequest, 
            $this->getUser()
        );

        return $this->redirect(
            $this->generateUrl("settings", ["success" => "Email verification sent successfully."])
        );
    }

    #[Route("/user/edit/email/verify", name: "user_edit_email_verify", methods: ["GET"])]
    public function userEditEmailVerify(EmailVerifyRequestDTO $emailVerifyRequest): Response
    {
        $this->userEditEmailService->editUserEmail($emailVerifyRequest, $this->getUser());

        return $this->redirect(
            $this->generateUrl("settings", ["success" => "User information edited successfully."])
        );
    }
}
