<?php

declare(strict_types=1);

namespace App\Controller;

use App\Factory\CouponFactory;
use App\Repository\CouponRepository;
use App\Service\CouponService;
use App\Validator\CouponValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CouponController extends AbstractController
{
    public function __construct(
        private readonly CouponRepository $couponRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly CouponValidator $couponValidator,
        private readonly CouponService $couponService,
    ) {
    }

    #[Route('/api/coupons', name: 'api_coupons_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $coupons = $this->couponRepository->findAll();

        return $this->json($this->couponService->getCouponsAsArray($coupons));
    }

    #[Route('/api/coupons/{id}', name: 'api_coupons_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $coupon = $this->couponRepository->find($id);
        if (!$coupon) {
            return $this->json(['error' => 'Coupon not found'], Response::HTTP_NOT_FOUND);
        }

        if (!$this->couponService->canShowCoupon($coupon)) {
            return $this->json(['error' => 'Coupon is not active or left count is equal 0'], Response::HTTP_BAD_REQUEST);
        }

        $coupon->reduceLeftCount();

        $this->entityManager->flush();

        return $this->json($coupon->getAsArray());
    }

    #[Route('/api/coupons', name: 'api_coupons_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = $request->query->all();

        $errorData = $this->couponValidator->validateCreateCouponData($data);
        if ($errorData) {
            return $this->json(['error' => $errorData], Response::HTTP_BAD_REQUEST);
        }

        $coupon = CouponFactory::create(
            $data['name'],
            $data['code'],
            (int) $data['leftCount'],
            (bool) $data['isActive']
        );

        $errorData = $this->couponValidator->validateCouponData($coupon);
        if ($errorData) {
            return $this->json(['error' => $errorData], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($coupon);
        $this->entityManager->flush();

        return $this->json($coupon, Response::HTTP_CREATED);
    }

    #[Route('/api/coupons/{id}', name: 'api_coupons_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $coupon = $this->couponRepository->find($id);
        if (!$coupon) {
            return $this->json(['error' => 'Coupon not found'], Response::HTTP_NOT_FOUND);
        }

        $data = $request->query->all();

        $this->couponService->updateCoupon($coupon, $data);

        $errors = $this->couponValidator->validateCouponData($coupon);
        if ($errors) {
            return $this->json(['error' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->flush();

        return $this->json($coupon->getAsArray());
    }

    #[Route('/api/coupons/{id}', name: 'api_coupons_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $coupon = $this->couponRepository->find($id);
        if (!$coupon) {
            return $this->json(['error' => 'Coupon not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($coupon);
        $this->entityManager->flush();

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}
