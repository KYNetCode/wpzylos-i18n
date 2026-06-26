<?php

declare(strict_types=1);

namespace WPZylos\Framework\I18n;

use WPZylos\Framework\Core\Contracts\ContextInterface;

/**
 * Translation wrapper.
 *
 * Provides localized strings using the plugin's text domain.
 * Always uses $context->textDomain() - never hardcoded.
 *
 * @package WPZylos\Framework\I18n
 */
class Translator
{
    /**
     * @var ContextInterface Plugin context
     */
    private ContextInterface $context;

    /**
     * Create translator.
     *
     * @param ContextInterface $context Plugin context
     */
    public function __construct(ContextInterface $context)
    {
        $this->context = $context;
    }

    /**
     * Translate a string.
     *
     * @param string $text Text to translate
     * @return string Translated text
     */
    public function translate(string $text): string
    {
        return __($text, $this->context->textDomain());
    }

    /**
     * Translate and echo a string.
     *
     * @param string $text Text to translate
     * @return void
     */
    public function echo(string $text): void
    {
        echo esc_html($this->translate($text));
    }

    /**
     * Translate with sprintf formatting.
     *
     * @param string $text Text with placeholders
     * @param mixed ...$args Replacement values
     * @return string Translated and formatted text
     */
    public function sprintf(string $text, mixed ...$args): string
    {
        return sprintf($this->translate($text), ...$args);
    }

    /**
     * Translate plural string.
     *
     * @param string $single Singular form
     * @param string $plural Plural form
     * @param int $number Count for determining form
     * @return string Translated text
     */
    public function plural(string $single, string $plural, int $number): string
    {
        return _n($single, $plural, $number, $this->context->textDomain());
    }

    /**
     * Translate with context.
     *
     * @param string $text Text to translate
     * @param string $context Translation context
     * @return string Translated text
     */
    public function translateWithContext(string $text, string $context): string
    {
        return _x($text, $context, $this->context->textDomain());
    }

    /**
     * Get the text domain.
     *
     * @return string
     */
    public function textDomain(): string
    {
        return $this->context->textDomain();
    }

    /**
     * Escape and translate for HTML.
     *
     * @param string $text Text to translate
     * @return string Escaped translated text
     */
    public function esc(string $text): string
    {
        return esc_html__($text, $this->context->textDomain());
    }

    /**
     * Escape and translate for attribute.
     *
     * @param string $text Text to translate
     * @return string Escaped translated text
     */
    public function escAttr(string $text): string
    {
        return esc_attr__($text, $this->context->textDomain());
    }
}
