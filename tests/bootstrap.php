<?php

declare(strict_types=1);

/**
 * PHPUnit bootstrap for i18n package.
 *
 * @phpcs:disable PSR1.Files.SideEffects
 */

if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__DIR__) . '/');
}

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Mock WordPress i18n functions
if (!function_exists('__')) {
    function __(string $text, string $domain = 'default'): string
    {
        return $text;
    }
}

if (!function_exists('_e')) {
    function _e(string $text, string $domain = 'default'): void
    {
        echo $text;
    }
}

if (!function_exists('_n')) {
    function _n(string $single, string $plural, int $count, string $domain = 'default'): string
    {
        return $count === 1 ? $single : $plural;
    }
}

if (!function_exists('_x')) {
    function _x(string $text, string $context, string $domain = 'default'): string
    {
        return $text;
    }
}

if (!function_exists('load_plugin_textdomain')) {
    function load_plugin_textdomain(string $domain, bool $deprecated = false, string $path = ''): bool
    {
        $GLOBALS['wpzylos_i18n_loaded_textdomain'] = compact('domain', 'deprecated', 'path');

        return true;
    }
}

if (!function_exists('plugin_basename')) {
    function plugin_basename(string $file): string
    {
        return 'example-plugin/' . basename($file);
    }
}

if (!function_exists('wp_set_script_translations')) {
    function wp_set_script_translations(string $handle, string $domain = 'default', string $path = ''): bool
    {
        $GLOBALS['wpzylos_i18n_script_translations'][$handle] = compact('handle', 'domain', 'path');

        return true;
    }
}

// Escaping functions
if (!function_exists('esc_html')) {
    function esc_html(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('esc_html__')) {
    function esc_html__(string $text, string $domain = 'default'): string
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('esc_attr__')) {
    function esc_attr__(string $text, string $domain = 'default'): string
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}
