<?php

namespace App\Interfaces;

use App\Entity\User;

interface RegistrationManagerInterface
{
    /**
     * Registers a new user in the system. 
     * @param User $user user entity from the registeration form
     * @param string $plainPassword plain password from the registeration form, use to hash and set the password of the user
     * @param string $accountType account type from the registeration form, use to define the role of the user
     * @return bool returns true if the user is registered successfully, false otherwise.
     */
    public function register(User $user, string $plainPassword, string $accountType): bool;
}
