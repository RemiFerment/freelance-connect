<?php

namespace App\Tests\Unit\Fixtures;

use App\Entity\User;

class UserFixture
{
    public static function createUserFixture(): User
    {
        return (new User())->setEmail("test@test.test")
            ->setFirstname("Test")
            ->setLastname("Unit")
            ->setAdress("test rue test")
            ->setCompanyName("Test inc")
            ->setPhone("0123456789")
            ->setCity("Testcity")
            ->setCountry("TestLand")
            ->setPostalCode("99999")
        ;
    }
}
