<?php

namespace App\Services;

use App\Entity\User;
use App\Interfaces\RegistrationManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegistrationManager implements RegistrationManagerInterface
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher, private EntityManagerInterface $entityManager) {}


    public function register(User $user, string $plainPassword, string $accountType): bool
    {
        try {
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
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            return false;
        }
        return true;
    }
}
