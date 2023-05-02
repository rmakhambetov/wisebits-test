<?php

namespace App\Repository;

interface BlackListRepository
{
    public function isBlacklisted(mixed $value): bool;
}
