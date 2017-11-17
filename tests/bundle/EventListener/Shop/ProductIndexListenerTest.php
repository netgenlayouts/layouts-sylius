<?php

namespace Netgen\Bundle\SyliusBlockManagerBundle\Tests\EventListener\Shop;

use Netgen\BlockManager\Context\Context;
use Netgen\BlockManager\Sylius\Tests\Stubs\Product;
use Netgen\BlockManager\Sylius\Tests\Stubs\Taxon;
use Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductIndexListener;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ProductIndexListenerTest extends TestCase
{
    /**
     * @var \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductIndexListener
     */
    private $listener;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $taxonRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $localeContextMock;

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;

    /**
     * @var \Netgen\BlockManager\Context\Context
     */
    private $context;

    public function setUp()
    {
        $this->taxonRepositoryMock = $this->createMock(TaxonRepositoryInterface::class);
        $this->localeContextMock = $this->createMock(LocaleContextInterface::class);
        $this->requestStack = new RequestStack();
        $this->context = new Context();

        $this->localeContextMock
            ->expects($this->any())
            ->method('getLocaleCode')
            ->will($this->returnValue('en'));

        $this->listener = new ProductIndexListener(
            $this->taxonRepositoryMock,
            $this->localeContextMock,
            $this->requestStack,
            $this->context
        );
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductIndexListener::__construct
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductIndexListener::getSubscribedEvents
     */
    public function testGetSubscribedEvents()
    {
        $this->assertEquals(
            array('sylius.product.index' => 'onProductIndex'),
            $this->listener->getSubscribedEvents()
        );
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductIndexListener::onProductIndex
     */
    public function testOnProductIndex()
    {
        $request = Request::create('/');
        $request->attributes->set('slug', 'mugs');

        $this->requestStack->push($request);

        $taxon = new Taxon(42);

        $this->taxonRepositoryMock
            ->expects($this->once())
            ->method('findOneBySlug')
            ->with($this->equalTo('mugs'), $this->equalTo('en'))
            ->will($this->returnValue($taxon));

        $event = new ResourceControllerEvent();
        $this->listener->onProductIndex($event);

        $this->assertEquals($taxon, $request->attributes->get('ngbm_sylius_taxon'));

        $this->assertTrue($this->context->has('sylius_taxon_id'));
        $this->assertEquals(42, $this->context->get('sylius_taxon_id'));
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductIndexListener::onProductIndex
     */
    public function testOnProductIndexWithoutRequest()
    {
        $this->taxonRepositoryMock
            ->expects($this->never())
            ->method('findOneBySlug');

        $event = new ResourceControllerEvent();
        $this->listener->onProductIndex($event);

        $this->assertFalse($this->context->has('sylius_taxon_id'));
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductIndexListener::onProductIndex
     */
    public function testOnProductIndexWithoutSlug()
    {
        $request = Request::create('/');
        $this->requestStack->push($request);

        $this->taxonRepositoryMock
            ->expects($this->never())
            ->method('findOneBySlug');

        $event = new ResourceControllerEvent();
        $this->listener->onProductIndex($event);

        $this->assertFalse($request->attributes->has('ngbm_sylius_taxon'));
        $this->assertFalse($this->context->has('sylius_taxon_id'));
    }

    /**
     * @covers \Netgen\Bundle\SyliusBlockManagerBundle\EventListener\Shop\ProductIndexListener::onProductIndex
     */
    public function testOnProductIndexWithNonExistingTaxon()
    {
        $request = Request::create('/');
        $request->attributes->set('slug', 'unknown');

        $this->requestStack->push($request);

        $this->taxonRepositoryMock
            ->expects($this->once())
            ->method('findOneBySlug')
            ->with($this->equalTo('unknown'), $this->equalTo('en'))
            ->will($this->returnValue(null));

        $event = new ResourceControllerEvent();
        $this->listener->onProductIndex($event);

        $this->assertFalse($request->attributes->has('ngbm_sylius_taxon'));
        $this->assertFalse($this->context->has('sylius_taxon_id'));
    }
}