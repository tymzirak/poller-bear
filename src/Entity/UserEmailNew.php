<?php

namespace App\Entity;

use App\Repository\UserEmailNewRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserEmailNewRepository::class)]
#[UniqueEntity(fields: ["user"], message: "Invalid user.")]
class UserEmailNew
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $emailNew;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmailNew(): string
    {
        return $this->emailNew;
    }

    public function setEmailNew(string $emailNew): self
    {
        $this->emailNew = $emailNew;

        return $this;
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
}
