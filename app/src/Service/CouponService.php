<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Coupon;
use Doctrine\ORM\EntityManagerInterface;

class CouponService implements CouponServiceInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function getCouponsAsArray(array $coupons): array
    {
        $data = [];
        foreach ($coupons as $coupon) {
            if ($this->canShowCoupon($coupon)) {
                $data[] = [
                    'id' => $coupon->getId(),
                    'name' => $coupon->getName(),
                    'code' => $coupon->getCode(),
                    'leftCount' => $coupon->getLeftCount(),
                    'isActive' => $coupon->isActive(),
                ];

                $coupon->reduceLeftCount();
            }
        }

        $this->entityManager->flush();

        return $data;
    }

    public function updateCoupon(Coupon $coupon, array $data): void
    {
        if (isset($data['name'])) {
            $coupon->setName($data['name']);
        }

        if (isset($data['isActive'])) {
            $coupon->setIsActive((bool) $data['isActive']);
        }
    }

    public function canShowCoupon(Coupon $coupon): bool
    {
        return $coupon->getLeftCount() > 0 && $coupon->isActive();
    }
}
