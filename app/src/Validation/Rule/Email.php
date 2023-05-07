<?php

namespace App\Validation\Rule;

use App\Repository\BlackListRepository;
use App\Validation\Rule;

final class Email implements Rule
{
    public function __construct(private BlackListRepository $repository)
    {
    }

    public function getRepository(): BlackListRepository
    {
        return $this->repository;
    }
}
