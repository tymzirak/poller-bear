<?php

namespace App\Entity;

use App\Repository\UserPasswordResetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserPasswordResetRepository::class)]
#[UniqueEntity(fields: ["user"], message: "Invalid user.")]
class UserPasswordReset
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'string', length: 1500)]
    private $passwordNew;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPasswordNew(): ?string
    {
        return $this->passwordNew;
    }

    public function setPasswordNew(string $passwordNew): self
    {
        $this->passwordNew = $passwordNew;

        return $this;
    }
}
