<?php

declare(strict_types=1);

namespace WPZylos\Framework\I18n;

use WPZylos\Framework\Core\Contracts\ContextInterface;

/**
 * I18n loader.
 *
 * Handles loading translation files for a plugin.
 *
 * @package WPZylos\Framework\I18n
 */
class I18n
{
    /**
     * Default folder for PO/MO and JS translation JSON files.
     */
    public const DEFAULT_LANGUAGES_PATH = 'resources/lang';

    /**
     * @var ContextInterface Plugin context
     */
    private ContextInterface $context;

    /**
     * @var bool Whether translations have been loaded
     */
    private bool $loaded = false;

    /**
     * Create i18n loader.
     *
     * @param ContextInterface $context Plugin context
     */
    public function __construct(ContextInterface $context)
    {
        $this->context = $context;
    }

    /**
     * Load translations.
     *
     * Calls load_plugin_textdomain with the correct paths.
     *
     * @return bool True if loaded successfully
     */
    public function load(): bool
    {
        if ($this->loaded) {
            return true;
        }

        $this->loaded = load_plugin_textdomain(
            $this->context->textDomain(),
            false,
            $this->getLanguagesRelativePath()
        );

        return $this->loaded;
    }

    /**
     * Static loader for convenience.
     *
     * @param ContextInterface $context Plugin context
     * @return bool
     */
    public static function loadFor(ContextInterface $context): bool
    {
        return ( new self($context) )->load();
    }

    /**
     * Check if translations are loaded.
     *
     * @return bool
     */
    public function isLoaded(): bool
    {
        return $this->loaded;
    }

    /**
     * Get the MO file path.
     *
     * @param string $locale Locale code (e.g., 'de_DE')
     * @return string Full path to MO file
     */
    public function getMoFilePath(string $locale): string
    {
        return $this->context->path(
            self::DEFAULT_LANGUAGES_PATH . '/' . $this->context->textDomain() . '-' . $locale . '.mo'
        );
    }

    /**
     * Attach WordPress JavaScript translations to a script handle.
     *
     * This uses the same text domain as PHP translations. JavaScript frameworks
     * such as Vue and React can then call wp.i18n.__('Original text', domain)
     * and use the original text as the gettext msgid.
     *
     * @param string $handle WordPress script handle.
     * @param string|null $languagesPath Absolute path to JSON translation files.
     * @return bool True when WordPress accepted the translation registration.
     */
    public function setScriptTranslations(string $handle, ?string $languagesPath = null): bool
    {
        if (!function_exists('wp_set_script_translations')) {
            return false;
        }

        $path = $languagesPath ?: $this->getLanguagesPath();

        return (bool) wp_set_script_translations(
            $handle,
            $this->context->textDomain(),
            $path
        );
    }

    /**
     * Attach JavaScript translations to multiple script handles.
     *
     * @param array<int,string> $handles WordPress script handles.
     * @param string|null $languagesPath Absolute path to JSON translation files.
     * @return array<string,bool> Per-handle registration result.
     */
    public function setScriptTranslationsFor(array $handles, ?string $languagesPath = null): array
    {
        $results = [];

        foreach ($handles as $handle) {
            $results[$handle] = $this->setScriptTranslations($handle, $languagesPath);
        }

        return $results;
    }

    /**
     * Static script translation helper for convenience.
     *
     * @param ContextInterface $context Plugin context.
     * @param string $handle WordPress script handle.
     * @param string|null $languagesPath Absolute path to JSON translation files.
     * @return bool
     */
    public static function setScriptTranslationsForContext(
        ContextInterface $context,
        string $handle,
        ?string $languagesPath = null
    ): bool {
        return (new self($context))->setScriptTranslations($handle, $languagesPath);
    }

    /**
     * Get the absolute languages directory path.
     *
     * @return string
     */
    public function getLanguagesPath(): string
    {
        return $this->context->path(self::DEFAULT_LANGUAGES_PATH);
    }

    /**
     * Get the languages directory relative to the WordPress plugins folder.
     *
     * @return string
     */
    public function getLanguagesRelativePath(): string
    {
        return dirname(plugin_basename($this->context->file())) . '/' . self::DEFAULT_LANGUAGES_PATH;
    }
}
