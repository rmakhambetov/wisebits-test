<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use App\Repository\UserRepositoryInterface;
use App\Validation\Validator;
use App\Entity\User;

final class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private UserValidationRuleProvider $validationRuleProvider,
        private Validator $validator,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @return \App\Entity\User[]
     */
    public function findAll(): array
    {
        return $this->userRepository->findAll();
    }

    public function findById(string $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    public function save(User $user): bool
    {
        $errors = $this->validator->validate($user, $this->validationRuleProvider->getSaveRules());

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }

        $result = $user->id
        ? $this->userRepository->update($user)
        : $this->userRepository->create($user);

        if (!$result) {
            throw new \RuntimeException('Cannot update/create user');
        }

        $this->logger->info(
            'User has been created/updated',
            (array)$user
        );

        return $result;
    }
}
