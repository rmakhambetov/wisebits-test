<?php

namespace App\Entity;

class User
{
    public function __construct(
        private readonly ?int $id,
        private readonly string $name,
        private readonly string $email,
        private readonly ?string $createdAt,
        private readonly ?string $deletedAt
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function getDeletedAt(): ?string
    {
        return $this->deletedAt;
    }

    public function getEmailDomain(): ?string
    {
        $parts = explode('@', $this->getEmail());

        return $parts[1] ?? null;
    }
}
