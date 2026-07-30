<?php

namespace App\Services;

use App\Entity\User;
use App\Interfaces\NotificationManagerInterface;

final class TempNotificationManager implements NotificationManagerInterface
{
    public function send(User $sender, User $recipient, string $message): void
    {
        var_dump($sender->getFirstname() . "has send to" . $recipient->getFirstname() . " this message : " . $message);
    }
}
