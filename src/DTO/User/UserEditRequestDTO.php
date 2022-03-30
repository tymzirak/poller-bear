<?php

namespace App\DTO\User;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\HttpFoundation\Request;

use App\Interface\DTO\RequestDTOInterface;

class UserEditRequestDTO implements RequestDTOInterface
{
    public UserRepository $userRepository;

    #[Assert\AtLeastOneOf([
        new Assert\Regex(pattern: "/^[a-zA-Z0-9_]{1,25}$/"),
        new Assert\Blank
    ], includeInternalMessages: false, message: "Username is invalid.")]
    public ?string $username;

    #[Assert\AtLeastOneOf([
        new Assert\Email,
        new Assert\Blank
    ], includeInternalMessages: false, message: "Email is invalid.")]
    public ?string $email;

    #[Assert\AtLeastOneOf([
        new SecurityAssert\UserPassword,
        new Assert\Blank
    ], includeInternalMessages: false, message: "Wrong password.")]
    public ?string $passwordOld;

    #[Assert\AtLeastOneOf([
        new Assert\Length(min: "8"),
        new Assert\Blank
    ], includeInternalMessages: false, message: "Password should be at least 8 characters.")]
    #[Assert\NotCompromisedPassword(message: "This password has been leaked. Please use another password.")]
    public ?string $passwordNew;

    #[Assert\EqualTo(propertyPath: "passwordNew", message: "Passwords do not match.")]
    public ?string $passwordNewRepeat;

    public function __construct(Request $request) {
        $data = json_decode($request->getContent(), true);

        $this->username = $data["username"] ?? null;
        $this->email = $data["email"] ?? null;
        $this->passwordOld = $data["password_old"] ?? null;
        $this->passwordNew = $data["password_new"] ?? null;
        $this->passwordNewRepeat = $data["password_new_repeat"] ?? null;
    }
}
