<?php

declare(strict_types=1);

namespace WPZylos\Framework\I18n\Tests\Unit;

use PHPUnit\Framework\TestCase;
use WPZylos\Framework\Core\Contracts\ContextInterface;
use WPZylos\Framework\I18n\I18n;

defined('ABSPATH') || exit;

/**
 * Tests for I18n class.
 *
 * Note: Full functional tests require WordPress runtime.
 * These tests verify the class structure and loadability.
 */
class I18nTest extends TestCase
{
    private function context(): ContextInterface
    {
        $context = $this->createMock(ContextInterface::class);
        $context->method('textDomain')->willReturn('test-domain');
        $context->method('file')->willReturn('/var/www/wp-content/plugins/example-plugin/plugin.php');
        $context->method('path')->willReturnCallback(
            static fn(string $path = ''): string => '/var/www/wp-content/plugins/example-plugin/' . ltrim($path, '/')
        );

        return $context;
    }

    public function testClassExists(): void
    {
        $this->assertTrue(class_exists(I18n::class));
    }

    public function testClassHasLoadMethod(): void
    {
        $this->assertTrue(method_exists(I18n::class, 'load'));
    }

    public function testLoadUsesDefaultLanguagesRelativePath(): void
    {
        $i18n = new I18n($this->context());

        $this->assertTrue($i18n->load());
        $this->assertSame(
            [
                'domain' => 'test-domain',
                'deprecated' => false,
                'path' => 'example-plugin/resources/lang',
            ],
            $GLOBALS['wpzylos_i18n_loaded_textdomain']
        );
    }

    public function testScriptTranslationsUseDefaultLanguagesPath(): void
    {
        $i18n = new I18n($this->context());

        $this->assertTrue($i18n->setScriptTranslations('example-admin'));
        $this->assertSame(
            [
                'handle' => 'example-admin',
                'domain' => 'test-domain',
                'path' => '/var/www/wp-content/plugins/example-plugin/resources/lang',
            ],
            $GLOBALS['wpzylos_i18n_script_translations']['example-admin']
        );
    }

    public function testScriptTranslationsCanUseCustomLanguagesPath(): void
    {
        $i18n = new I18n($this->context());

        $this->assertTrue($i18n->setScriptTranslations('example-frontend', '/tmp/languages'));
        $this->assertSame('/tmp/languages', $GLOBALS['wpzylos_i18n_script_translations']['example-frontend']['path']);
    }

    public function testScriptTranslationsCanRegisterMultipleHandles(): void
    {
        $i18n = new I18n($this->context());

        $this->assertSame(
            [
                'example-admin' => true,
                'example-frontend' => true,
            ],
            $i18n->setScriptTranslationsFor(['example-admin', 'example-frontend'])
        );
    }
}
