<?php

namespace Netgen\BlockManager\Sylius\Validator;

use Netgen\BlockManager\Sylius\Validator\Constraint\Product;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class ProductValidator extends ConstraintValidator
{
    /**
     * @var \Sylius\Component\Product\Repository\ProductRepositoryInterface
     */
    private $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        if ($value === null) {
            return;
        }

        if (!$constraint instanceof Product) {
            throw new UnexpectedTypeException($constraint, Product::class);
        }

        if (!is_scalar($value)) {
            throw new UnexpectedTypeException($value, 'scalar');
        }

        $product = $this->productRepository->find($value);
        if (!$product instanceof ProductInterface) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('%productId%', $value)
                ->addViolation();
        }
    }
}