<?php

namespace App\Service\Email;

use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use SymfonyCasts\Bundle\VerifyEmail\Model\VerifyEmailSignatureComponents;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use App\Entity\User;

class EmailSendVerificationService
{
    private VerifyEmailHelperInterface $verifyEmailHelper;
    private MailerInterface $mailer;

    public function __construct(VerifyEmailHelperInterface $verifyEmailHelper, MailerInterface $mailer) 
    {
        $this->verifyEmailHelper = $verifyEmailHelper;
        $this->mailer = $mailer;
    }

    public function sendEmailVerification(User $user, string $route)
    {
        $this->mailer->send(
            $this->getEmail(
                $this->getEmailVerificationSignature($user, $route)
            )
        );
    }

    private function getEmailVerificationSignature(User $user, string $route): VerifyEmailSignatureComponents
    {
        return $this->verifyEmailHelper->generateSignature(
            $route,
            $user->getId(),
            $user->getEmail(),
            ["user-id" => $user->getId()]
        );
    }

    private function getEmail(VerifyEmailSignatureComponents $signatureComponents): Email
    {
        return (new Email())
            ->from("hello@example.com")
            ->to("you@example.com")
            ->text($signatureComponents->getSignedUrl());
    }
}
