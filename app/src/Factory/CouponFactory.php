<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Coupon;

class CouponFactory
{
    public static function create(
        string $name,
        string $code,
        int $leftCount,
        bool $isActive
    ): Coupon {
        $coupon = new Coupon();
        $coupon->setName($name);
        $coupon->setCode($code);
        $coupon->setLeftCount($leftCount);
        $coupon->setIsActive($isActive);

        return $coupon;
    }
}
