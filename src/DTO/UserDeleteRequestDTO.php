<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\HttpFoundation\Request;

use App\DTO\RequestDTOInterface;

class UserDeleteRequestDTO implements RequestDTOInterface
{
    #[SecurityAssert\UserPassword(message: "Wrong password.")]
    public string $password;

    #[Assert\EqualTo(propertyPath: "password", message: "Passwords do not match.")]
    public string $passwordRepeat;

    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $this->password = $data["password"];
        $this->passwordRepeat = $data["password_repeat"];
    }
}
