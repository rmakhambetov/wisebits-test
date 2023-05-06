<?php

namespace App\Validation\Rule;

use App\Repository\UniqueAbleRepository;
use App\Validation\Rule;

final class Unique implements Rule
{
    public function __construct(public mixed $id, public string $name, private UniqueAbleRepository $repository)
    {
    }

    public function getRepository(): UniqueAbleRepository
    {
        return $this->repository;
    }
}
