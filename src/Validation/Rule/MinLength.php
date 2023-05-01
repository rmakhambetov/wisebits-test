<?php

namespace Validation\Rule;

use Validation\Rule;

final class MinLength implements Rule
{
  public function __construct(public int $minLength)
  {
  }
}