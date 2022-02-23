<?php

namespace App\Service;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolation;

use App\Entity\User;
use App\Service\ViolationService;

class UserService
{
    private UserPasswordHasherInterface $passwordHasher;
    private ValidatorInterface $validator;
    private ViolationService $violationService;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        ValidatorInterface $validator,
        ViolationService $violationService
    ) {
        $this->passwordHasher = $passwordHasher;
        $this->validator = $validator;

        $this->violationService = $violationService;
    }

    public function hashUserPassword(User $user): User
    {
        $passwordHash = $this->passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($passwordHash);

        return $user;
    }

    public function getLastUserEntityViolation(User $user, string $property=null): ?ConstraintViolation
    {
        $violations = $property ?
                      $this->validator->validateProperty($user, $property) :
                      $this->validator->validate($user);

        return $this->violationService->getLastViolation($violations);
    }
}
