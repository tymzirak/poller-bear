<?php

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

use App\Entity\User;

class ViolationService
{
    private UserPasswordHasherInterface $passwordHasher;
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator) {
        $this->validator = $validator;
    }

    public function getViolations(object $object, string $property=null): ConstraintViolationList
    {
        $violations = $property ?
                      $this->validator->validateProperty($object, $property) :
                      $this->validator->validate($object);

        return $violations;
    }

    public function getLastViolationFromList(ConstraintViolationList $violations): ?ConstraintViolation
    {
        $violationsCount = $violations->count();

        return $violationsCount ? $violations->get($violationsCount - 1) : null;
    }

    public function getLastViolation(object $object, string $property=null): ?ConstraintViolation
    {
        $violations = $this->getViolations($object, $property);

        return $this->getLastViolationFromList($violations);
    }
}
