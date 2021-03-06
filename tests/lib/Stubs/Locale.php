<?php

declare(strict_types=1);

namespace Netgen\Layouts\Sylius\Tests\Stubs;

use Sylius\Component\Locale\Model\Locale as BaseLocale;

final class Locale extends BaseLocale
{
    public function __construct(int $id, string $code)
    {
        parent::__construct();

        $this->id = $id;
        $this->code = $code;
    }
}
