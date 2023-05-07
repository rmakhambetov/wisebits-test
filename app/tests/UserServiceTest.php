<?php

namespace Tests;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use App\Service\UserValidationRuleProvider;
use App\Validation\RuleHandlerRegistry\InMemoryRuleHandlerRegistry;
use App\Validation\Validator;
use App\Service\UserService;

final class UserServiceTest extends \PHPUnit\Framework\TestCase
{
  /**
   * @return void
   * @dataProvider usersProvider
   * @throws \PHPUnit\Framework\MockObject\Exception
   */
    public function testFindAll(array $users)
    {
        $userRepo = $this->createMock(UserRepositoryInterface::class);
        $userRepo->method('findAll')->willReturn($users);
        $service = $this->createService($userRepo);
        self::assertCount(2, $service->findAll());
    }

  /**
   * @return void
   * @dataProvider usersProvider
   * @throws \PHPUnit\Framework\MockObject\Exception
   */
    public function testFindById(array $users)
    {
        $userRepo = $this->createMock(UserRepositoryInterface::class);
        $userRepo->method('findById')->willReturnCallback(fn(int $id) => $users[$id]);
        $service = $this->createService($userRepo);
        self::assertEquals(
            new User(1, 'roman makh', 'rmakhambetov@test.ru', '2023-05-01'),
            $service->findById(1)
        );
    }

  /**
   * @dataProvider usersProvider
   */
    public function testFindByInvalidId(array $users)
    {
        $userRepo = $this->createMock(UserRepositoryInterface::class);
        $userRepo->method('findById')->willReturnCallback(fn(int $id) => $users[$id]);
        $service = $this->createService($userRepo);
        self::assertNull($service->findById(3));
    }

    public function testUpdateUser()
    {
        $userRepo = $this->createMock(UserRepositoryInterface::class);
        $userRepo->expects($this->once())->method('update')->willReturn(true);
        $service = $this->createService($userRepo);
        $user = new User(1, 'roman123', 'rmakhambetov@test1.ru', '2023-05-01');
        self::assertTrue($service->save($user));
    }

    public function testCreateUser()
    {
        $userRepo = $this->createMock(UserRepositoryInterface::class);
        $userRepo->expects($this->once())->method('create')->willReturn(true);
        $service = $this->createService($userRepo);
        $user = new User(null, 'roman1234', 'rmakhambetov@test2.ru', '2023-05-01');
        self::assertTrue($service->save($user));
    }

    public function testCreateInvalidUser()
    {
        $this->expectException(\App\Service\ValidationException::class);
        $userRepo = $this->createMock(UserRepositoryInterface::class);
        $userRepo->expects($this->never())->method('create');
        $userRepo->expects($this->never())->method('update');
        $service = $this->createService($userRepo);
        $user = new User(null, 'roma', 'rmakhambetov@test1.ru', '2023-05-01');
        $service->save($user);
    }

    public function testUpdateInvalidUser()
    {
        $this->expectException(\App\Service\ValidationException::class);
        $userRepo = $this->createMock(UserRepositoryInterface::class);
        $userRepo->expects($this->never())->method('create');
        $userRepo->expects($this->never())->method('update');
        $service = $this->createService($userRepo);
        $user = new User(1, 'roma', 'rmakhambetov@test1.ru', '2023-05-01');
        $service->save($user);
    }

    private function createService(UserRepositoryInterface $userRepository): \App\Service\UserService
    {
        $unuqueRepo = new \Tests\Repository\Stubs\SimpleUniqueRepository();
        $blackListRepo = new \Tests\Repository\Stubs\SimpleBlacklistRepository();
        $logger = $this->createMock(\Psr\Log\LoggerInterface::class);
        $validationProvider = new UserValidationRuleProvider(
            1,
            $unuqueRepo,
            $blackListRepo,
            $blackListRepo,
        );

        return new UserService($userRepository, $validationProvider, $this->createValidator(), $logger);
    }

    private function createValidator(): Validator
    {
        return new Validator(
            new InMemoryRuleHandlerRegistry(
                [
                new \App\Validation\Rule\AllHandler(),
                new \App\Validation\Rule\AnyHandler(),
                new \App\Validation\Rule\EmailHandler(),
                new \App\Validation\Rule\AlphaNumericHandler(),
                new \App\Validation\Rule\ForbiddenValueHandler(),
                new \App\Validation\Rule\MinLengthHandler(),
                new \App\Validation\Rule\NotNullHandler(),
                new \App\Validation\Rule\ObjectPropertiesHandler(),
                new \App\Validation\Rule\UniqueHandler(),
                ]
            )
        );
    }

    public static function usersProvider(): array
    {
        return [[
        [
        1 => new User(1, 'roman makh', 'rmakhambetov@test.ru', '2023-05-01'),
        2 => new User(2, 'oleg test', 'oleg@test.ru', '2023-05-02'),
        ]
        ]];
    }
}
