<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Service\UserSignupService;

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
    public function signupInit(Request $request): Response
    {
        $requestData = $request->request->all();

        if (!$error = $this->userSignupService->getLastUserActionViolation($requestData)) {
            return $this->redirect($this->generateUrl("login"));
        }

        return $this->render("auth/signup.html.twig", ["error" => $error]);
    }

    #[Route("/password/reset", name: "password_reset", methods: ["GET"])]
    public function password_reset(): Response
    {
        return $this->render("auth/reset_password.html.twig");
    }

    #[Route("/logout", name: "logout", methods: ["GET"])]
    public function logout(): Response {}
}
