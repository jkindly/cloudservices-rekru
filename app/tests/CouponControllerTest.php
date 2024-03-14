<?php

declare(strict_types=1);

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class CouponControllerTest extends ApiTestCase
{
    protected function tearDown(): void
    {
        self::assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testIndex(): void
    {
        static::createClient()->request('GET', '/api/coupons');

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
    }

    public function testShow(): void
    {
        static::createClient()->request('GET', '/api/coupons/1');

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
    }

    public function testCreate(): void
    {
        static::createClient()->request('POST', '/api/coupons', ['query' => [
            'name' => 'Test',
            'code' => 'TEST',
            'leftCount' => 10,
            'isActive' => true,
        ]]);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(201);
    }

    public function testCreateWithInvalidData(): void
    {
        static::createClient()->request('POST', '/api/coupons', ['query' => [
            'name' => 'Test',
            'code' => 'TEST',
            'leftCount' => -1,
            'isActive' => true,
        ]]);

        self::assertResponseStatusCodeSame(400);
    }

    public function testUpdate(): void
    {
        static::createClient()->request('PUT', '/api/coupons/1', ['query' => [
            'name' => 'Test',
            'isActive' => false,
        ]]);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
    }

    public function testUpdateNotFound(): void
    {
        static::createClient()->request('PUT', '/api/coupons/1000', ['query' => [
            'name' => 'Test',
            'isActive' => false,
        ]]);

        self::assertResponseStatusCodeSame(404);
    }

    public function testShowNotFound(): void
    {
        static::createClient()->request('GET', '/api/coupons/1000');

        self::assertResponseStatusCodeSame(404);
    }
}
