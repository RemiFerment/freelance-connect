<?php

namespace App\Tests\Unit\Fake;

use App\Entity\User;
use App\Interfaces\RegistrationManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegisterManagerFake implements RegistrationManagerInterface
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher) {}
    public function register(User $user, string $plainPassword, string $accountType): bool
    {
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $plainPassword));

        switch ($accountType) {
            case 'freelance':
                $user->setRoles(['ROLE_FREELANCE']);
                break;
            default:
            case 'client':
                $user->setRoles(['ROLE_CLIENT']);
                break;
        }
        return true;
    }
}
