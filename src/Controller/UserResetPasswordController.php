<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\User\UserSendResetPasswordVerificationService;
use App\Service\User\UserResetPasswordService;
use App\DTO\User\UserSendPasswordResetRequestDTO;
use App\DTO\Email\EmailVerifyRequestDTO;
use App\DTO\Captcha\CaptchaRequestDTO;

class UserResetPasswordController extends AbstractController
{
    private UserSendResetPasswordVerificationService $userSendResetPasswordVerificationService;
    private UserResetPasswordService $userResetPasswordService;

    public function __construct(
        UserSendResetPasswordVerificationService $userSendResetPasswordVerificationService,
        UserResetPasswordService $userResetPasswordService,
    ) {
        $this->userSendResetPasswordVerificationService = $userSendResetPasswordVerificationService;
        $this->userResetPasswordService = $userResetPasswordService;
    }

    #[Route("/password/reset", name: "password_reset", methods: ["GET"])]
    public function passwordReset(): Response
    {
        return $this->render("auth/password_reset.html.twig");
    }

    #[Route("/password/reset/init", name: "password_reset_init", methods: ["POST"])]
    public function passwordResetInit(
        CaptchaRequestDTO $captchaRequest,
        UserSendPasswordResetRequestDTO $userSendPasswordResetRequest
    ): Response {
        $this->userSendResetPasswordVerificationService->sendUserResetPasswordVerification(
            $userSendPasswordResetRequest
        );
        
        return $this->redirect(
            $this->generateUrl("password_reset", ["success" => "Email sent successfully."])
        );
    }

    #[Route("/password/verify", name: "password_verify", methods: ["GET"])]
    public function passwordVerify(EmailVerifyRequestDTO $emailVerifyRequest): Response
    {
        $this->userResetPasswordService->resetUserPassword($emailVerifyRequest);

        return $this->redirectToRoute("login", ["success" => "Password reset successfully."]);
    }
}
