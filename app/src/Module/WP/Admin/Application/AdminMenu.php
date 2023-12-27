<?php

namespace App\Module\WP\Admin\Application;

use App\Module\WP\Plugin\Domain\PluginNameConstant;

final class AdminMenu
{
    private static bool $loaded = false;

    public function __construct(
        private readonly AdminHandler $adminHandler
    ) {}

    public function addMenu(): void
    {
        if (!self::$loaded) {
            add_action('admin_menu', function() {
                $response = $this->adminHandler->handleRequest();

                add_menu_page('', 'Symfony App', 'manage_options', PluginNameConstant::NAME,
                    function () use($response) {
                        $this->adminHandler->handleResponse($response);
                    });
            });

            self::$loaded = true;
        }
    }
}
