<?php

namespace App\DTO\UserInformation;

use Symfony\Component\HttpFoundation\Request;

use App\Interface\DTO\RequestDTOInterface;
use App\Validator\UserInformation as UserInformationAssert;

class UserInformationAddRequestDTO implements RequestDTOInterface
{
    #[UserInformationAssert\UserInformationName]
    private string $username;

    public function __construct(Request $request)
    {
        $this->username = $request->request->get("username");
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}
