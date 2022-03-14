<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

use App\Repository\UserRepository;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ["username"], message: "There is already an account with this username.")]
#[UniqueEntity(fields: ["email"], message: "There is already an account with this email.")]
class User implements UserInterface, PasswordAuthenticatedUserInterface, EquatableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 25)]
    private $username;

    #[ORM\Column(type: "string", length: 50)]
    private $email;

    #[ORM\Column(type: "string", length: 255)]
    private $password;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see EquatableInterface
     */
    public function isEqualTo(UserInterface $user): bool
    {
        return true;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return ["ROLE_USER"];
    }

    /**
     * @see UserInterface
     */
    public function getSalt() {}

    /**
     * @see UserInterface
     */
    public function eraseCredentials() {}

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }
}
