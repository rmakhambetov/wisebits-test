<?php

namespace App\Entity;

final class User
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $createdAt = null,
        public readonly ?string $deletedAt = null
    ) {
    }

    public function getEmailDomain(): ?string
    {
        $parts = explode('@', $this->getEmail());

        return $parts[1] ?? null;
    }
}
