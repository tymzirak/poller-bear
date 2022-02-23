<?php

namespace App\Service;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\ConstraintViolation;

use App\Entity\User;

class UserDeleteService
{
    private TokenStorageInterface $tokenStorage;
    private UserPasswordHasherInterface $passwordHasher;
    private ManagerRegistry $doctrine;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        UserPasswordHasherInterface $passwordHasher,
        ManagerRegistry $doctrine
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->passwordHasher = $passwordHasher;
        $this->doctrine = $doctrine;
    }

    public function getLastUserActionViolation(array $requestData, ?User $user): ?ConstraintViolation
    {
        if ($violation = $this->getLastUserDeleteRequestViolation($requestData, $user)) {
            return $violation;
        }

        $this->deleteUser($user);

        return null;
    }

    private function getLastUserDeleteRequestViolation(array $requestData, ?User $user): ?ConstraintViolation
    {
        if (!$user || !$requestData["password"] || !$requestData["password_repeat"]) {
            return new ConstraintViolation("", "", [], null, "", null);
        }

        if ($requestData["password"] != $requestData["password_repeat"]) {
            return new ConstraintViolation("Passwords do not match.", "", [], null, "", null);
        }

        if (!$this->passwordHasher->isPasswordValid($user, $requestData["password"])) {
            return new ConstraintViolation("Password is invalid.", "", [], null, "", null);
        }

        return null;
    }

    private function deleteUser(User $user)
    {
        $this->tokenStorage->setToken(null);

        $entityManager = $this->doctrine->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
    }
}
