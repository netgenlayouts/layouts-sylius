<?php

namespace Netgen\BlockManager\Sylius\Item\ValueUrlGenerator;

use Netgen\BlockManager\Item\ValueUrlGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class ProductValueUrlGenerator implements ValueUrlGeneratorInterface
{
    /**
     * @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function generate($object)
    {
        return $this->urlGenerator->generate(
            'sylius_shop_product_show',
            array(
                'slug' => $object->getSlug(),
            )
        );
    }
}