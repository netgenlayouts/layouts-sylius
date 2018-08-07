<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Sylius\Tests\Item\ValueLoader;

use Exception;
use Netgen\BlockManager\Sylius\Item\ValueLoader\ProductValueLoader;
use Netgen\BlockManager\Sylius\Tests\Item\Stubs\Product;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;

final class ProductValueLoaderTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $productRepositoryMock;

    /**
     * @var \Netgen\BlockManager\Sylius\Item\ValueLoader\ProductValueLoader
     */
    private $valueLoader;

    public function setUp(): void
    {
        $this->productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);
        $this->valueLoader = new ProductValueLoader($this->productRepositoryMock);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueLoader\ProductValueLoader::__construct
     * @covers \Netgen\BlockManager\Sylius\Item\ValueLoader\ProductValueLoader::load
     */
    public function testLoad(): void
    {
        $product = new Product(42, 'Product name');

        $this->productRepositoryMock
            ->expects(self::any())
            ->method('find')
            ->with(self::identicalTo(42))
            ->will(self::returnValue($product));

        self::assertSame($product, $this->valueLoader->load(42));
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueLoader\ProductValueLoader::load
     */
    public function testLoadWithNoProduct(): void
    {
        $this->productRepositoryMock
            ->expects(self::any())
            ->method('find')
            ->with(self::identicalTo(42))
            ->will(self::returnValue(null));

        self::assertNull($this->valueLoader->load(42));
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueLoader\ProductValueLoader::load
     */
    public function testLoadWithRepositoryException(): void
    {
        $this->productRepositoryMock
            ->expects(self::any())
            ->method('find')
            ->with(self::identicalTo(42))
            ->will(self::throwException(new Exception()));

        self::assertNull($this->valueLoader->load(42));
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueLoader\ProductValueLoader::loadByRemoteId
     */
    public function testLoadByRemoteId(): void
    {
        $product = new Product(42, 'Product name');

        $this->productRepositoryMock
            ->expects(self::any())
            ->method('find')
            ->with(self::identicalTo('abc'))
            ->will(self::returnValue($product));

        self::assertSame($product, $this->valueLoader->loadByRemoteId('abc'));
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueLoader\ProductValueLoader::loadByRemoteId
     */
    public function testLoadByRemoteIdWithNoProduct(): void
    {
        $this->productRepositoryMock
            ->expects(self::any())
            ->method('find')
            ->with(self::identicalTo('abc'))
            ->will(self::returnValue(null));

        self::assertNull($this->valueLoader->loadByRemoteId('abc'));
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueLoader\ProductValueLoader::loadByRemoteId
     */
    public function testLoadByRemoteIdWithRepositoryException(): void
    {
        $this->productRepositoryMock
            ->expects(self::any())
            ->method('find')
            ->with(self::identicalTo('abc'))
            ->will(self::throwException(new Exception()));

        self::assertNull($this->valueLoader->loadByRemoteId('abc'));
    }
}
