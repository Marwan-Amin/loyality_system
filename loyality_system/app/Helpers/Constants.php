<?php

namespace App\Helpers;

class Constants
{
    const GIFT_POINTS = 500;
    const STATUS_PENDING = "pending";
    const STATUS_CONFIRMED = "confirmed";
    const STATUS_EXPIRED = "expired";
    const STATUS = [self::STATUS_CONFIRMED, self::STATUS_EXPIRED, self::STATUS_PENDING];
}
