<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\User\UserVerifyService;
use App\Service\User\UserSignupService;
use App\Service\UserInformation\UserInformationAddService;
use App\Service\Email\EmailSendVerificationService;
use App\DTO\User\UserSignupRequestDTO;
use App\DTO\UserInformation\UserInformationAddRequestDTO;
use App\DTO\Email\EmailVerifyRequestDTO;

class UserSignupController extends AbstractController
{
    private UserVerifyService $userVerifyService;
    private UserSignupService $userSignupService;
    private UserInformationAddService $userInformationAddService;
    private EmailSendVerificationService $emailSendVerificationService;

    public function __construct(
        UserVerifyService $userVerifyService,
        UserSignupService $userSignupService,
        UserInformationAddService $userInformationAddService,
        EmailSendVerificationService $emailSendVerificationService
    ) {
        $this->userVerifyService = $userVerifyService;
        $this->userSignupService = $userSignupService;
        $this->userInformationAddService = $userInformationAddService;
        $this->emailSendVerificationService = $emailSendVerificationService;
    }

    #[Route("/signup/verify", name: "signup_verify", methods: ["GET"])]
    public function signupVerify(EmailVerifyRequestDTO $emailVerifyRequest): Response
    {
        $this->userVerifyService->verifyUser($emailVerifyRequest);

        return $this->redirectToRoute("login", ["success" => "Email verified successfully."]);
    }

    #[Route("/signup", name: "signup", methods: ["GET"])]
    public function signupCustomer() : Response
    {
        return $this->render("auth/signup.html.twig");
    }

    #[Route("/signup/init", name: "signup_init", methods: ["POST"])]
    public function signupCustomerInit(
        UserSignupRequestDTO $userSignupRequest,
        UserInformationAddRequestDTO $userInformationAddRequest,
    ): Response {
        $user = $this->userSignupService->signupUser($userSignupRequest);
        
        $userInformation = $this->userInformationAddService->addUserInformation(
            $userInformationAddRequest,
            $user
        );

        $this->emailSendVerificationService->sendEmailVerification($user, "signup_verify");

        return $this->redirect(
            $this->generateUrl("login", ["success" => "Email verification sent successfully."])
        );
    }
}
