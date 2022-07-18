<?php

namespace App\Service\User;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Violation\ViolationService;
use App\DTO\User\UserSignupRequestDTO;

class UserSignupService
{
    private UserPasswordHasherInterface $passwordHasher;

    private UserRepository $userRepository;
    private ViolationService $violationService;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher, 
        UserRepository $userRepository,
        ViolationService $violationService
    ) {
        $this->passwordHasher = $passwordHasher;

        $this->userRepository = $userRepository;
        $this->violationService = $violationService;
    }

    public function signupUser(UserSignupRequestDTO $userSignupRequest): User 
    {
        $user = $this->setUserProperties($userSignupRequest, new User());

        if ($violation = $this->violationService->getLastViolation($user)) {
            throw new BadRequestHttpException($violation->getMessage());
        }

        $this->userRepository->add($user, true);

        return $user;
    }

    private function setUserProperties(UserSignupRequestDTO $userSignupRequest, User $user): User 
    {
        $user->setEmail($userSignupRequest->getEmail());
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                $userSignupRequest->getPassword()
            )
        );

        return $user;
    }
}
