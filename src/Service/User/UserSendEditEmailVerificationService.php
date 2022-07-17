<?php

namespace App\Service\User;

use App\Entity\User;
use App\Entity\UserEmailNew;
use App\Repository\UserEmailNewRepository;
use App\Service\Email\EmailSendVerificationService;
use App\DTO\Email\EmailEditRequestDTO;

class UserSendEditEmailVerificationService
{
    private UserEmailNewRepository $userEmailNewRepository;
    private EmailSendVerificationService $emailSendVerificationService;

    public function __construct(
        UserEmailNewRepository $userEmailNewRepository, 
        EmailSendVerificationService $emailSendVerificationService 
    ) {
        $this->userEmailNewRepository = $userEmailNewRepository;
        $this->emailSendVerificationService = $emailSendVerificationService;
    }

    public function sendUserEditEmailVerification(EmailEditRequestDTO $emailEditRequest, User $user)
    {
        if ($userEmailNew = $this->userEmailNewRepository->findOneBy(["user" => $user])) {
            $this->userEmailNewRepository->remove($userEmailNew, true);
        }

        $this->emailSendVerificationService->sendEmailVerification($user, "user_edit_email_verify");
        
        $userEmailNew = $this->setUserEmailNewProperties($emailEditRequest, new UserEmailNew(), $user);
        
        $this->userEmailNewRepository->add($userEmailNew, true);
    }

    private function setUserEmailNewProperties(
        EmailEditRequestDTO $emailEditRequest, 
        UserEmailNew $userEmailNew,
        User $user
    ): UserEmailNew {
        $userEmailNew->setEmailNew($emailEditRequest->getEmail());
        $userEmailNew->setUser($user);

        return $userEmailNew;
    }
}
