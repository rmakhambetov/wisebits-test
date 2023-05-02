<?php

namespace App\Validation\Rule;

use App\Validation\Rule;

final class MinLength implements Rule
{
    public function __construct(public int $minLength)
    {
    }
}
