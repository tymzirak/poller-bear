<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use App\Service\UserSignupService;
use App\DTO\UserSignupRequestDTO;

class AuthController extends AbstractController
{
    private UserSignupService $userSignupService;

    public function __construct(UserSignupService $userSignupService)
    {
        $this->userSignupService = $userSignupService;
    }

    #[Route("/login", name: "login", methods: ["GET", "POST"])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render("auth/login.html.twig", ["error" => $error, "last_username" => $lastUsername]);
    }

    #[Route("/signup", name: "signup", methods: ["GET"])]
    public function signup(): Response
    {
        return $this->render("auth/signup.html.twig");
    }

    #[Route("/signup/init", name: "signup_init", methods: ["POST"])]
    public function signupInit(UserSignupRequestDTO $request): Response
    {
        try {
            $this->userSignupService->attemptToSignupUser($request);
            return $this->redirect($this->generateUrl("login"));
        } catch (BadRequestHttpException $error) {
            return new Response($error->getMessage());
        }
    }

    #[Route("/password/reset", name: "password_reset", methods: ["GET"])]
    public function passwordReset(): Response
    {
        return $this->render("auth/reset_password.html.twig");
    }

    #[Route("/logout", name: "logout", methods: ["GET"])]
    public function logout(): Response {}
}
