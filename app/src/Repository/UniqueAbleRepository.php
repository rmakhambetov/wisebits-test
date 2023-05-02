<?php

namespace App\Repository;

interface UniqueAbleRepository
{
    public function isUnique(mixed $id, string $name, mixed $value): bool;
}
