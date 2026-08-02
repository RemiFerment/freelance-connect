<?php

namespace App\Enum;

/**
 * Enum coresponding with actual Mission status on DB
 */
enum MissionStatusEnum: string
{
    case PENDING = "PENDING";
    case IN_PROGRESS = "IN_PROGRESS";
    case COMPLETED = "COMPLETED";
    case CANCELED = "CANCELED";
}
