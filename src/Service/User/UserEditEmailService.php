<?php

namespace App\Service\User;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use App\Entity\User;
use App\Entity\UserEmailNew;
use App\Repository\UserRepository;
use App\Repository\UserEmailNewRepository;
use App\Service\Email\EmailVerifyService;
use App\Service\Violation\ViolationService;
use App\DTO\Email\EmailVerifyRequestDTO;

class UserEditEmailService
{
    private UserRepository $userRepository;
    private UserEmailNewRepository $userEmailNewRepository;
    private EmailVerifyService $emailVerifyService;
    private ViolationService $violationService;

    public function __construct(
        UserRepository $userRepository, 
        UserEmailNewRepository $userEmailNewRepository, 
        EmailVerifyService $emailVerifyService,
        ViolationService $violationService
    ) {
        $this->userRepository = $userRepository;
        $this->userEmailNewRepository = $userEmailNewRepository;
        $this->emailVerifyService = $emailVerifyService;
        $this->violationService = $violationService;
    }

    public function editUserEmail(EmailVerifyRequestDTO $emailVerifyRequest): User
    {
        $user = $this->emailVerifyService->verifyEmail($emailVerifyRequest);
        
        $userEmailNew = $this->userEmailNewRepository->findOneBy(["user" => $user]);
        $user = $this->setUserProperties($userEmailNew, $user);

        if ($violation = $this->violationService->getLastViolation($user)) {
            throw new BadRequestHttpException($violation->getMessage());
        }

        $this->userEmailNewRepository->remove($userEmailNew, true);
        $this->userRepository->add($user, true);

        return $user;
    }

    private function setUserProperties(UserEmailNew $userEmailNew, User $user): User 
    {
        $user->setEmail($userEmailNew->getEmailNew());

        return $user;
    }
}
