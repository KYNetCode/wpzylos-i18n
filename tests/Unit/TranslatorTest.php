<?php

declare(strict_types=1);

namespace WPZylos\Framework\I18n\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WPZylos\Framework\Core\Contracts\ContextInterface;
use WPZylos\Framework\I18n\Translator;

defined('ABSPATH') || exit;

/**
 * Tests for Translator class.
 */
class TranslatorTest extends TestCase
{
    private Translator $translator;

    protected function setUp(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $context->method('textDomain')
            ->willReturn('test-domain');

        $this->translator = new Translator($context);
    }

    public function testTranslateReturnsString(): void
    {
        $result = $this->translator->translate('Hello World');

        $this->assertSame('Hello World', $result);
    }

    public function testPluralizeReturnsSingleForOne(): void
    {
        $result = $this->translator->plural('item', 'items', 1);

        $this->assertSame('item', $result);
    }

    public function testPluralizeReturnsPluralForMany(): void
    {
        $result = $this->translator->plural('item', 'items', 5);

        $this->assertSame('items', $result);
    }

    public function testContextualTranslation(): void
    {
        $result = $this->translator->translateWithContext('Post', 'noun');

        $this->assertSame('Post', $result);
    }
}
