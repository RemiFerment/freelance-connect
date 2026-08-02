<?php

namespace App\Tests\Unit;

use App\Tests\Unit\Fake\RegisterManagerFake;
use App\Tests\Unit\Fixtures\UserFixture;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterTest extends TestCase
{
    public function testRegisterClientUser(): void
    {
        $userFixture = UserFixture::createUserFixture();
        $plainPassword = "RemiLeaPoolvorde*78";
        $accountType = "client";

        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHasher
            ->method('hashPassword')
            ->willReturn('hashed_password');

        $registerManagerFake = new RegisterManagerFake($passwordHasher);
        $test = $registerManagerFake->register($userFixture, $plainPassword, $accountType);

        $this->assertTrue($test);
    }
}
