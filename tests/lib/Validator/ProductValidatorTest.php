<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Validator;

use Netgen\Layouts\Sylius\Tests\Stubs\Product as ProductStub;
use Netgen\Layouts\Sylius\Validator\Constraint\Product;
use Netgen\Layouts\Sylius\Validator\ProductValidator;
use Netgen\Layouts\Tests\TestCase\ValidatorTestCase;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class ProductValidatorTest extends ValidatorTestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $repositoryMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->constraint = new Product();
    }

    public function getValidator(): ConstraintValidatorInterface
    {
        $this->repositoryMock = $this->createMock(ProductRepositoryInterface::class);

        return new ProductValidator($this->repositoryMock);
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Validator\ProductValidator::__construct
     * @covers \Netgen\Layouts\Sylius\Validator\ProductValidator::validate
     */
    public function testValidateValid(): void
    {
        $this->repositoryMock
            ->expects(self::once())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(new ProductStub(42));

        self::assertValid(true, 42);
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Validator\ProductValidator::__construct
     * @covers \Netgen\Layouts\Sylius\Validator\ProductValidator::validate
     */
    public function testValidateNull(): void
    {
        $this->repositoryMock
            ->expects(self::never())
            ->method('find');

        self::assertValid(true, null);
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Validator\ProductValidator::__construct
     * @covers \Netgen\Layouts\Sylius\Validator\ProductValidator::validate
     */
    public function testValidateInvalid(): void
    {
        $this->repositoryMock
            ->expects(self::once())
            ->method('find')
            ->with(self::identicalTo(42))
            ->willReturn(null);

        self::assertValid(false, 42);
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Validator\ProductValidator::validate
     */
    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidConstraint(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage('Expected argument of type "Netgen\\Layouts\\Sylius\\Validator\\Constraint\\Product", "Symfony\\Component\\Validator\\Constraints\\NotBlank" given');

        $this->constraint = new NotBlank();
        self::assertValid(true, 'value');
    }

    /**
     * @covers \Netgen\Layouts\Sylius\Validator\ProductValidator::validate
     */
    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidValue(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage('Expected argument of type "scalar", "array" given');

        self::assertValid(true, []);
    }
}
