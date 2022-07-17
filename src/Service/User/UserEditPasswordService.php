<?php

namespace App\Service\User;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use App\Entity\User;
use App\Repository\UserRepository;
use App\DTO\User\UserEditPasswordRequestDTO;

class UserEditPasswordService
{
    private UserPasswordHasherInterface $passwordHasher;

    private UserRepository $userRepository;

    public function __construct(UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository)
    {
        $this->passwordHasher = $passwordHasher;

        $this->userRepository = $userRepository;
    }

    public function editUserPassword(UserEditPasswordRequestDTO $userEditPasswordRequest, User $user): User 
    {
        $user = $this->setUserProperties($userEditPasswordRequest, $user);

        $this->userRepository->add($user, true);

        return $user;
    }

    private function setUserProperties(UserEditPasswordRequestDTO $userEditPasswordRequest, User $user): User
    {
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                $userEditPasswordRequest->getPasswordNew()
            )
        );

        return $user;
    }
}
