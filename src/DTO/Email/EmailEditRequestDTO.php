<?php

namespace App\DTO\Email;

use Symfony\Component\HttpFoundation\Request;

use App\Interface\DTO\RequestDTOInterface;
use App\Validator\User as UserAssert;

class EmailEditRequestDTO implements RequestDTOInterface
{
    #[UserAssert\UserEmailAvailable]
    private string $email;

    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $this->email = $data["email"];
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
