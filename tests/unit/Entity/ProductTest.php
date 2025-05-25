<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Product;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testConstructorSetCreatedAt(): void
    {
        $product = new Product();

        $this->assertInstanceOf(DateTimeImmutable::class, $product->getCreatedAt());
        // La fecha creada debe ser cercana a ahora (por ejemplo, max 2 segundos de diferencia)
        $now = new DateTimeImmutable();
        $diff = $now->getTimestamp() - $product->getCreatedAt()->getTimestamp();
        $this->assertLessThanOrEqual(2, abs($diff));
    }

    public function testGetSetName(): void
    {
        $product = new Product();
        $name = 'Mi producto';

        $product->setName($name);
        $this->assertSame($name, $product->getName());
    }

    public function testGetSetPrice(): void
    {
        $product = new Product();
        $price = 99.99;

        $product->setPrice($price);
        $this->assertSame($price, $product->getPrice());
    }

    public function testGetSetCreatedAt(): void
    {
        $product = new Product();
        $date = new DateTimeImmutable('2023-01-01 12:34:56');

        $product->setCreatedAt($date);
        $this->assertSame($date, $product->getCreatedAt());
    }

    public function testGetIdInitiallyNull(): void
    {
        $product = new Product();
        $this->assertNull($product->getId());
    }
}