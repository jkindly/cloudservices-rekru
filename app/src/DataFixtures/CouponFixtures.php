<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Coupon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CouponFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $coupon = new Coupon();
            $coupon->setName('Coupon ' . $i);
            $coupon->setCode('CODE' . $i);
            $coupon->setLeftCount(10);
            $coupon->setIsActive(true);

            $manager->persist($coupon);
        }

        $manager->flush();
    }
}
