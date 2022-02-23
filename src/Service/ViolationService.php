<?php

namespace App\Service;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class ViolationService
{
    public function getLastViolation(ConstraintViolationList $violations): ?ConstraintViolation
    {
        return $violations->count() ? $violations->get($violations->count() - 1) : null;
    }
}
