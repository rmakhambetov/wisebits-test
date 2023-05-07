<?php

namespace Tests\Repository\Stubs;

use App\Repository\UniqueAbleRepository;

final class SimpleUniqueRepository implements UniqueAbleRepository
{
    private const RECORDS = [['id' => 1, 'name' => 'roman'], ['id' => 2, 'name' => 'oleg']];

    public function isUnique(mixed $id, string $name, mixed $value): bool
    {
        foreach (self::RECORDS as $record) {
            if ($record[$name] === $value && $record['id'] !== $id) {
                return false;
            }
        }

        return true;
    }
}
