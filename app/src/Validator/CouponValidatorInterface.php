<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\Coupon;

interface CouponValidatorInterface
{
    public function validateCreateCouponData(array $data): array;

    public function validateCouponData(Coupon $coupon): array;
}
