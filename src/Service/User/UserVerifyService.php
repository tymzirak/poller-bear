<?php

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Email\EmailVerifyService;
use App\DTO\Email\EmailVerifyRequestDTO;

class UserVerifyService
{
    private EmailVerifyService $emailVerifyService;
    private UserRepository $userRepository;

    public function __construct(EmailVerifyService $emailVerifyService, UserRepository $userRepository) 
    {
        $this->emailVerifyService = $emailVerifyService;
        $this->userRepository = $userRepository;
    }

    public function verifyUser(EmailVerifyRequestDTO $emailVerifyRequestDTO)
    {
        $user = $this->emailVerifyService->verifyEmail($emailVerifyRequestDTO);

        $user = $this->setUserProperties($user);

        $this->userRepository->add($user, true);
    }

    private function setUserProperties(User $user): User
    {
        $user->setIsVerified(true);

        return $user;
    }
}
