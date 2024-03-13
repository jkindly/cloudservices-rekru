<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\Coupon;
use App\Repository\CouponRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CouponValidator implements CouponValidatorInterface
{
    public function __construct(
        private readonly CouponRepository $couponRepository,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function validateCreateCouponData(array $data): array
    {
        $error = [];
        if (!isset($data['name'])) {
            $error[] = 'Argument "name" is required';
        }

        if (!isset($data['code'])) {
            $error[] = 'Argument "code" is required';
        }

        if (!isset($data['leftCount'])) {
            $error[] = 'Argument "leftCount" is required';
        }

        if (!isset($data['isActive'])) {
            $error[] = 'Argument "isActive" is required';
        }

        if ($this->couponRepository->findOneBy(['code' => $data['code']])) {
            $error[] = 'Coupon with this code already exists';
        }

        return $error;
    }

    public function validateCouponData(Coupon $coupon): array
    {
        $errors = $this->validator->validate($coupon);

        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[$error->getPropertyPath()] = $error->getMessage();
        }

        return $errorMessages;
    }
}
