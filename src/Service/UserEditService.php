<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use App\Entity\User;
use App\Service\UserService;
use App\Service\ViolationService;
use App\DTO\UserEditRequestDTO;

class UserEditService
{
    private ManagerRegistry $doctrine;
    private ViolationService $violationService;

    private UserService $userService;

    private array $editableProperties = [];

    public function __construct(
        ManagerRegistry $doctrine,
        ViolationService $violationService,
        UserService $userService
    ) {
        $this->doctrine = $doctrine;
        $this->violationService = $violationService;

        $this->userService = $userService;
    }

    public function attemptToEditUser(UserEditRequestDTO $userEditRequestDTO, User $user)
    {
        if (!$this->setEditableProperties($userEditRequestDTO, $user)) {
            throw new BadRequestHttpException("At least one field must be edited.");
        }

        $user = $this->setUserEditProperties($userEditRequestDTO, $user);

        if ($violation = $this->violationService->getLastViolation($user)) {
            throw new BadRequestHttpException($violation->getMessage());
        }

        $this->editUser($user);
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

    private function setUserEditProperties(UserEditRequestDTO $userEditRequestDTO, User $user): ?User
    {
        if ($this->isEditableProperty("username")) {
            $user->setUsername($userEditRequestDTO->username);
        }

        if ($this->isEditableProperty("email")) {
            $user->setEmail($userEditRequestDTO->email);
        }

        if ($this->isEditableProperty("password")) {
            $user->setPassword($userEditRequestDTO->passwordNew);
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

    private function setEditableProperties(UserEditRequestDTO $userEditRequestDTO, User $user): array
    {
        if ($userEditRequestDTO->username && $user->getUsername() != $userEditRequestDTO->username) {
            $this->addEditableProperty("username");
        }

        if ($userEditRequestDTO->email && $user->getEmail() != $userEditRequestDTO->email) {
            $this->addEditableProperty("email");
        }

        if ($userEditRequestDTO->passwordOld && $userEditRequestDTO->passwordNew) {
            $this->addEditableProperty("password");
        }

        return $this->editableProperties;
    }
}
