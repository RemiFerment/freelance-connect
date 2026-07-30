<?php

namespace App\Interfaces;

use App\Entity\User;

interface NotificationManagerInterface
{
    public function send(User $sender, User $recipient, string $message): void;
}
