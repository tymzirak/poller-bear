<?php

namespace App\Service\User;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use App\Repository\UserRepository;
use App\Service\Email\EmailVerifyService;
use App\DTO\Email\EmailVerifyRequestDTO;

class UserDeleteService
{
    private TokenStorageInterface $tokenStorage;

    private UserRepository $userRepository;
    private EmailVerifyService $emailVerifyService;

    public function __construct(
        TokenStorageInterface $tokenStorage, 
        UserRepository $userRepository, 
        EmailVerifyService $emailVerifyService
    ) {
        $this->tokenStorage = $tokenStorage;

        $this->userRepository = $userRepository;
        $this->emailVerifyService = $emailVerifyService;
    }

    public function deleteUser(EmailVerifyRequestDTO $emailVerifyRequest) 
    {
        $user = $this->emailVerifyService->verifyEmail($emailVerifyRequest);

        $this->userRepository->remove($user, true);

        $this->tokenStorage->setToken(null);
    }
}
