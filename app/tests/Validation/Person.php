<?php

namespace Tests\Validation;

final class Person
{
    public string $name;

    public int $age;

    public function __construct(string $name = 'Roman', int $age = 26)
    {
        $this->name = $name;
        $this->age = $age;
    }
}
