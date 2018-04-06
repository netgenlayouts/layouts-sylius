<?php

namespace Netgen\BlockManager\Sylius\Tests\Layout\Resolver\TargetHandler\Doctrine;

use Netgen\BlockManager\Sylius\Layout\Resolver\TargetHandler\Doctrine\TaxonProduct;
use Netgen\BlockManager\Tests\Layout\Resolver\TargetHandler\Doctrine\AbstractTargetHandlerTest;

final class TaxonProductTest extends AbstractTargetHandlerTest
{
    /**
     * @covers \Netgen\BlockManager\Persistence\Doctrine\Handler\LayoutResolverHandler::matchRules
     * @covers \Netgen\BlockManager\Persistence\Doctrine\QueryHandler\LayoutResolverQueryHandler::matchRules
     * @covers \Netgen\BlockManager\Sylius\Layout\Resolver\TargetHandler\Doctrine\TaxonProduct::handleQuery
     */
    public function testMatchRules()
    {
        $rules = $this->handler->matchRules($this->getTargetIdentifier(), array(1, 2, 42));

        $this->assertCount(1, $rules);
        $this->assertEquals(6, $rules[0]->id);
    }

    /**
     * Returns the target identifier under test.
     *
     * @return string
     */
    protected function getTargetIdentifier()
    {
        return 'sylius_taxon_product';
    }

    /**
     * Creates the handler under test.
     *
     * @return \Netgen\BlockManager\Persistence\Doctrine\QueryHandler\TargetHandlerInterface
     */
    protected function getTargetHandler()
    {
        return new TaxonProduct();
    }

    /**
     * Inserts database fixtures.
     *
     * @param string $fixturesPath
     */
    protected function insertDatabaseFixtures($fixturesPath)
    {
        parent::insertDatabaseFixtures(__DIR__ . '/../../../../../_fixtures');
    }
}