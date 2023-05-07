<?php

namespace App\Service;

use App\Repository\BlackListRepository;
use App\Repository\UniqueAbleRepository;
use App\Validation\Rule;
use App\Validation\Rule\All;
use App\Validation\Rule\AlphaNumeric;
use App\Validation\Rule\Any;
use App\Validation\Rule\Email;
use App\Validation\Rule\ForbiddenValue;
use App\Validation\Rule\MinLength;
use App\Validation\Rule\NotNull;
use App\Validation\Rule\ObjectProperties;
use App\Validation\Rule\Unique;

final class UserValidationRuleProvider
{
    public function __construct(
        private readonly ?int $userId,
        private UniqueAbleRepository $userRepository,
        private BlackListRepository $blackListNameRepository,
        private BlackListRepository $blackListDomainRepository
    ) {
    }

    public function getSaveRules(): Rule
    {
        return new All(
            [
            new ObjectProperties(
                [
                'name'  => new Any(
                    [
                    new NotNull(),
                    new Unique($this->userId, 'name', $this->userRepository),
                    new ForbiddenValue($this->blackListNameRepository),
                    new AlphaNumeric(),
                    new MinLength(8),
                    ]
                ),
                'email' => new Any(
                    [
                    new NotNull(),
                    new Unique($this->userId, 'email', $this->userRepository),
                    new Email($this->blackListDomainRepository),
                    ]
                ),
                ]
            ),
            ]
        );
    }
}
