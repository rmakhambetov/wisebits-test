<?php

namespace Validation\Rule;

use Repository\BlackListRepository;
use Validation\Rule;

final class ForbiddenValue implements Rule
{
  public function __construct(private BlackListRepository $repository)
  {
  }

  public function getRepository(): BlackListRepository
  {
    return $this->repository;
  }
}