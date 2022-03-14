<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

use App\DTO\RequestDTOInterface;

class UserSignupRequestDTO implements RequestDTOInterface
{
    #[Assert\Regex(pattern: "/^[a-zA-Z0-9_]{1,25}$/", message: "Username is invalid.")]
    public string $username;

    #[Assert\Email(message: "Email is invalid.")]
    public string $email;

    #[Assert\Length(min: "8", minMessage: "Password should be at least 8 characters.")]
    #[Assert\NotCompromisedPassword(message: "This password has been leaked. Please use another password.")]
    public string $password;

    #[Assert\EqualTo(propertyPath: "password", message: "Passwords do not match.")]
    public string $passwordRepeat;

    public function __construct(Request $request) {
        $data = json_decode($request->getContent(), true);

        $this->username = $data["username"];
        $this->email = $data["email"];
        $this->password = $data["password"];
        $this->passwordRepeat = $data["password_repeat"];
    }
}
