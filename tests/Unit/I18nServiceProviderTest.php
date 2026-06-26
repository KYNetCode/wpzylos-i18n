<?php

declare(strict_types=1);

namespace WPZylos\Framework\I18n\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WPZylos\Framework\I18n\I18nServiceProvider;

defined('ABSPATH') || exit;

/**
 * Tests for I18nServiceProvider.
 */
class I18nServiceProviderTest extends TestCase
{
    public function testProviderIsInstantiable(): void
    {
        $provider = new I18nServiceProvider();
        $this->assertInstanceOf(I18nServiceProvider::class, $provider);
    }
}
