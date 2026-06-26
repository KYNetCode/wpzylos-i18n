<?php

declare(strict_types=1);

namespace WPZylos\Framework\I18n;

use WPZylos\Framework\Core\Contracts\ApplicationInterface;
use WPZylos\Framework\Core\ServiceProvider;

/**
 * I18n service provider.
 *
 * Registers translator and loads translations.
 *
 * @package WPZylos\Framework\I18n
 */
class I18nServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function register(ApplicationInterface $app): void
    {
        parent::register($app);

        $this->singleton(Translator::class, fn() => new Translator($app->context()));
        $this->singleton('translator', fn() => $this->make(Translator::class));

        $this->singleton(I18n::class, fn() => new I18n($app->context()));
    }

    /**
     * {@inheritDoc}
     */
    public function boot(ApplicationInterface $app): void
    {
        // Load translations on init hook
        add_action('init', function () {
            $i18n = $this->make(I18n::class);
            $i18n->load();
        });
    }
}
