<?php

namespace App\Repository;

use App\Entity\User;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;

    public function findAll(): array;

    public function update(User $user): bool;

    public function create(User $user): int;

    public function softDelete(User $user): bool;
}
