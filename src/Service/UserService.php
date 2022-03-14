<?php

namespace App\Service;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use App\Entity\User;

class UserService
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher) {
        $this->passwordHasher = $passwordHasher;
    }

    public function hashUserPassword(User $user): User
    {
        $passwordHash = $this->passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($passwordHash);

        return $user;
    }
}
