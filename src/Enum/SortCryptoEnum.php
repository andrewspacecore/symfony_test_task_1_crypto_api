<?php declare(strict_types=1);

namespace App\Enum;

enum SortCryptoEnum: string
{
    case HOUR = 'hour';
    case TWOHOUR = 'twohour';
    case DAY = 'day';
    case WEEK = 'week';
    case MONTH = 'month';
}