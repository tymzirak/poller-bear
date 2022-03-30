<?php

namespace App\Service\User;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\User;

class UserDeleteService
{
    private TokenStorageInterface $tokenStorage;
    private ManagerRegistry $doctrine;

    public function __construct(TokenStorageInterface $tokenStorage, ManagerRegistry $doctrine) {
        $this->tokenStorage = $tokenStorage;
        $this->doctrine = $doctrine;
    }

    public function deleteUser(User $user)
    {
        $this->tokenStorage->setToken(null);

        $entityManager = $this->doctrine->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
    }
}
