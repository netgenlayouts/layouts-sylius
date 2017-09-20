<?php

namespace Netgen\BlockManager\Sylius\Tests\Item\ValueUrlBuilder;

use Netgen\BlockManager\Sylius\Item\ValueUrlBuilder\ProductValueUrlBuilder;
use Netgen\BlockManager\Sylius\Tests\Item\Stubs\Product;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;

class ProductValueUrlBuilderTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $router;

    /**
     * @var \Netgen\BlockManager\Sylius\Item\ValueUrlBuilder\ProductValueUrlBuilder
     */
    private $urlBuilder;

    public function setUp()
    {
        $this->router = $this->createMock(RouterInterface::class);

        $this->urlBuilder = new ProductValueUrlBuilder($this->router);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Item\ValueUrlBuilder\ProductValueUrlBuilder::__construct
     * @covers \Netgen\BlockManager\Sylius\Item\ValueUrlBuilder\ProductValueUrlBuilder::getUrl
     */
    public function testGetUrl()
    {
        $this->router
            ->expects($this->once())
            ->method('generate')
            ->with(
                $this->equalTo('sylius_shop_product_show'),
                array(
                    'slug' => 'product-name',
                )
            )
            ->will($this->returnValue('/products/product-name'));

        $this->assertEquals(
            '/products/product-name',
            $this->urlBuilder->getUrl(new Product(42, 'Product name', 'product-name'))
        );
    }
}
