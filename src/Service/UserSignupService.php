<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\ConstraintViolation;

use App\Entity\User;
use App\Service\UserService;

class UserSignupService
{
    private ManagerRegistry $doctrine;

    private UserService $userService;

    public function __construct(ManagerRegistry $doctrine, UserService $userService)
    {
        $this->doctrine = $doctrine;

        $this->userService = $userService;
    }

    public function getLastUserActionViolation(array $requestData): ?ConstraintViolation
    {
        if ($violation = $this->getLastUserSignupRequestViolation($requestData)) {
            return $violation;
        }

        $user = new User();
        $user = $this->setUserSignupProperties($requestData, $user);

        if ($violation = $this->userService->getLastUserEntityViolation($user)) {
            return $violation;
        }

        $this->signupUser($user);

        return null;
    }

    private function getLastUserSignupRequestViolation(array $requestData): ?ConstraintViolation
    {
        if (
            !$requestData["username"]
            || !$requestData["email"]
            || !$requestData["password"]
            || !$requestData["password_repeat"]
        ) {
            return new ConstraintViolation("", "", [], null, "", null);
        }

        if ($requestData["password"] != $requestData["password_repeat"]) {
            return new ConstraintViolation("Passwords do not match.", "", [], null, "", null);
        }

        return null;
    }

    private function signupUser(User $user)
    {
        $user = $this->userService->hashUserPassword($user);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
    }

    private function setUserSignupProperties(array $requestData, User $user): User
    {
        $user->setUsername($requestData["username"]);
        $user->setEmail($requestData["email"]);
        $user->setPassword($requestData["password"]);

        return $user;
    }
}
