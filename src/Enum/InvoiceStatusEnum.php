<?php

namespace App\Enum;

/**
 * Enum coresponding with actual Mission status on DB
 */
enum InvoiceStatusEnum: string
{
    case PENDING = "PENGING";
    case ACCEPTED = "PAID";
}
