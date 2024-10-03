<?php

declare(strict_types=1);

namespace App\Entity;

use App\Exception\Entity\User\InvalidEmailException;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: '`users`')]
class User
{
    protected const CANONICAL_FORMAT = 'Y-m-d H:i:s';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $name = null;

    #[ORM\Column(length: 150)]
    private ?string $email = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        if (!self::isValidEmail($email)) {
            throw InvalidEmailException::create($email);
        }
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getCreatedAtString(): ?string
    {
        if (!$this->createdAt instanceof \DateTimeImmutable) {
            return null;
        }

        return $this->createdAt->format(self::CANONICAL_FORMAT);
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtNow(): self
    {
        return $this->setCreatedAt(new \DateTimeImmutable('now'));
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getUpdatedAtString(): ?string
    {
        if (!$this->updatedAt instanceof \DateTimeImmutable) {
            return null;
        }

        return $this->updatedAt->format(self::CANONICAL_FORMAT);
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtNow(): self
    {
        return $this->setUpdatedAt(new \DateTimeImmutable('now'));
    }

    protected static function isValidEmail(string $email): bool
    {
        return \filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
