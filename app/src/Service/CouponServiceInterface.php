<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Coupon;

interface CouponServiceInterface
{
    public function getCouponsAsArray(array $coupons): array;

    public function updateCoupon(Coupon $coupon, array $data): void;

    public function canShowCoupon(Coupon $coupon): bool;
}
