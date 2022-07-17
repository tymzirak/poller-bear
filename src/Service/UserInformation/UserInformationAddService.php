<?php

namespace App\Service\UserInformation;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use App\Entity\UserInformation;
use App\Entity\User;
use App\Repository\UserInformationRepository;
use App\Repository\UserRepository;
use App\DTO\UserInformation\UserInformationAddRequestDTO;

class UserInformationAddService
{
    private UserInformationRepository $userInformationRepository;
    private UserRepository $userRepository;

    public function __construct(
        UserInformationRepository $userInformationRepository, 
        UserRepository $userRepository, 
    ) {
        $this->userInformationRepository = $userInformationRepository;
        $this->userRepository = $userRepository;
    }

    public function addUserInformation(
        UserInformationAddRequestDTO $userInformationAddRequest,
        User $user
    ): UserInformation {
        $userInformation = $this->setUserInformationProperties(
            $userInformationAddRequest,
            new UserInformation(),
        );
        $this->setUserProperties($userInformation, $user);
        
        $this->userInformationRepository->add($userInformation, true);
        $this->userRepository->add($user, true);

        return $userInformation;
    }

    private function setUserInformationProperties(
        UserInformationAddRequestDTO $userInformationAddRequest,
        UserInformation $userInformation,
    ): UserInformation {
        $userInformation->setUsername($userInformationAddRequest->getUsername());

        return $userInformation;
    }

    private function setUserProperties(UserInformation $userInformation, User $user): User 
    {
        $user->setUserInformation($userInformation);

        return $user;
    }
}
