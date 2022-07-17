<?php

namespace App\Validator\UserInformation;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UserInformationName extends Constraint
{
    public string $message = 'The name is not valid.';
}
