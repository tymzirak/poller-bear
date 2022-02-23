<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\ConstraintViolation;

use App\Entity\User;
use App\Service\UserService;

class UserEditService
{
    private UserPasswordHasherInterface $passwordHasher;
    private ManagerRegistry $doctrine;

    private UserService $userService;

    private $editableProperties = [];

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        ManagerRegistry $doctrine,
        UserService $userService
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->doctrine = $doctrine;

        $this->userService = $userService;
    }

    public function getLastUserActionViolation(array $requestData, ?User $user): ?ConstraintViolation
    {
        if ($violation = $this->getLastUserEditRequestViolation($requestData, $user)) {
            return $violation;
        }

        $user = $this->setUserEditProperties($requestData, $user);

        if ($violation = $this->userService->getLastUserEntityViolation($user)) {
            return $violation;
        }

        $this->editUser($user);

        return null;
    }

    private function getLastUserEditRequestViolation(array $requestData, ?User $user): ?ConstraintViolation
    {
        if (!$user || !$this->setEditableProperties($requestData, $user)) {
            return new ConstraintViolation("", "", [], null, "", null);
        }

        if ($this->isEditableProperty("password")) {
            if ($requestData["password_new"] != $requestData["password_new_repeat"]) {
                return new ConstraintViolation("Passwords do not match.", "", [], null, "", null);
            }

            if (!$this->passwordHasher->isPasswordValid($user, $requestData["password_old"])) {
                return new ConstraintViolation("Password is not valid.", "", [], null, "", null);
            }
        }

        return null;
    }

    private function editUser(User $user)
    {
        if ($this->isEditableProperty("password")) {
            $user = $this->userService->hashUserPassword($user);
        }

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
    }

    private function setUserEditProperties(array $requestData, User $user): ?User
    {
        if ($this->isEditableProperty("username")) {
            $user->setUsername($requestData["username"]);
        }

        if ($this->isEditableProperty("email")) {
            $user->setEmail($requestData["email"]);
        }

        if ($this->isEditableProperty("password")) {
            $user->setPassword($requestData["password_new"]);
        }

        return $user;
    }

    private function isEditableProperty(string $property): bool
    {
        return in_array($property, $this->editableProperties);
    }

    private function addEditableProperty(string $property)
    {
        $this->editableProperties[] = $property;
    }

    private function setEditableProperties(array $requestData, User $user): array
    {
        if ($requestData["username"] && $user->getUsername() != $requestData["username"]) {
            $this->addEditableProperty("username");
        }

        if ($requestData["email"] && $user->getEmail() != $requestData["email"]) {
            $this->addEditableProperty("email");
        }

        if (
            $requestData["password_old"]
            && $requestData["password_new"]
            && $requestData["password_new_repeat"]
        ) {
            $this->addEditableProperty("password");
        }

        return $this->editableProperties;
    }
}
