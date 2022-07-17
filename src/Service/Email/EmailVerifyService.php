<?php

namespace App\Service\Email;

use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

use App\Entity\User;
use App\Repository\UserRepository;
use App\DTO\Email\EmailVerifyRequestDTO;

class EmailVerifyService
{
    private VerifyEmailHelperInterface $verifyEmailHelper;
    
    private UserRepository $userRepository;

    public function __construct(
        VerifyEmailHelperInterface $verifyEmailHelper,
        UserRepository $userRepository
    ) {
        $this->verifyEmailHelper = $verifyEmailHelper;
        
        $this->userRepository = $userRepository;
    }

    public function verifyEmail(EmailVerifyRequestDTO $emailVerifyRequest): User
    {
        $user = $this->userRepository->find($emailVerifyRequest->getUserId());

        $this->verifyEmailHelper->validateEmailConfirmation(
            $emailVerifyRequest->getRequestUri(),
            $user->getId(),
            $user->getEmail()
        );

        return $user;
    }
}
