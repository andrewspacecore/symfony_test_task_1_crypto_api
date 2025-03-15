<?php declare(strict_types=1);

namespace App\Enum;

enum SortCryptoPriceEnum: string
{
    case HOUR = 'hour';
    case TWOHOUR = 'twohour';
    case DAY = 'day';
    case WEEK = 'week';
    case MONTH = 'month';

    /**
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}