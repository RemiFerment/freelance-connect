<?php

namespace App\Enum;

/**
 * Enum coresponding with actual Mission status on DB
 */
enum CandidacyStatusEnum: string
{
    case PENDING = "PENGING";
    case ACCEPTED = "ACCEPTED";
    case REFUSED = "REFUSED";
}
