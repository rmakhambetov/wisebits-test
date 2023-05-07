<?php

namespace Tests\Repository\Stubs;

use App\Repository\BlackListRepository;

final class SimpleBlacklistRepository implements BlackListRepository
{
    private const BLACKLIST = ['curseword1', 'curseword2', 123, 'domain.org'];

    public function isBlacklisted(mixed $value): bool
    {
        return in_array($value, self::BLACKLIST, true);
    }
}
