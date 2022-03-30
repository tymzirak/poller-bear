<?php

namespace App\Service\User;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use App\Entity\User;
use App\Service\User\UserService;
use App\Service\Violation\ViolationService;
use App\DTO\User\UserSignupRequestDTO;

class UserSignupService
{
    private ManagerRegistry $doctrine;
    private ViolationService $violationService;

    private UserService $userService;

    public function __construct(
        ManagerRegistry $doctrine,
        ViolationService $violationService,
        UserService $userService
    ) {
        $this->doctrine = $doctrine;
        $this->violationService = $violationService;

        $this->userService = $userService;
    }

    public function attemptToSignupUser(UserSignupRequestDTO $userSignupRequestDTO)
    {
        $user = new User();
        $user = $this->setUserSignupProperties($userSignupRequestDTO, $user);

        if ($violation = $this->violationService->getLastViolation($user)) {
            throw new BadRequestHttpException($violation->getMessage());
        }

        $this->signupUser($user);
    }

    private function signupUser(User $user)
    {
        $user = $this->userService->hashUserPassword($user);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
    }

    private function setUserSignupProperties(UserSignupRequestDTO $userSignupRequestDTO, User $user): User
    {
        $user->setUsername($userSignupRequestDTO->username);
        $user->setEmail($userSignupRequestDTO->email);
        $user->setPassword($userSignupRequestDTO->password);

        return $user;
    }
}
