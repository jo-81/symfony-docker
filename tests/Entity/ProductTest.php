<?php

namespace App\Tests\Entity;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testProduct(): void
    {
        $product = new Product();

        $product->setName('Clavier');
        $product->setPrice(4999);

        self::assertSame('Clavier', $product->getName());
        self::assertSame(4999, $product->getPrice());
    }
}
