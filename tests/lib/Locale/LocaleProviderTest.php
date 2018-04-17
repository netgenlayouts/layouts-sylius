<?php

namespace Netgen\BlockManager\Sylius\Tests\Locale;

use Netgen\BlockManager\Sylius\Locale\LocaleProvider;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Locale\Provider\LocaleProviderInterface;
use Symfony\Component\HttpFoundation\Request;

final class LocaleProviderTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $syliusLocaleProviderMock;

    /**
     * @var \Netgen\BlockManager\Sylius\Locale\LocaleProvider
     */
    private $localeProvider;

    public function setUp()
    {
        $this->syliusLocaleProviderMock = $this->createMock(LocaleProviderInterface::class);

        $this->localeProvider = new LocaleProvider($this->syliusLocaleProviderMock);
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Locale\LocaleProvider::__construct
     * @covers \Netgen\BlockManager\Sylius\Locale\LocaleProvider::getAvailableLocales
     */
    public function testGetAvailableLocales()
    {
        $this->syliusLocaleProviderMock
            ->expects($this->any())
            ->method('getAvailableLocalesCodes')
            ->will($this->returnValue(['en', 'de', 'hr']));

        $availableLocales = $this->localeProvider->getAvailableLocales();

        $this->assertEquals(['hr', 'en', 'de'], array_keys($availableLocales));
        $this->assertEquals(['Croatian', 'English', 'German'], array_values($availableLocales));
    }

    /**
     * @covers \Netgen\BlockManager\Sylius\Locale\LocaleProvider::getRequestLocales
     */
    public function testGetRequestLocales()
    {
        $request = Request::create('');
        $request->setDefaultLocale('hr');

        $requestLocales = $this->localeProvider->getRequestLocales($request);

        $this->assertEquals(['hr'], $requestLocales);
    }
}
