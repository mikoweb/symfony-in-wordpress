<?php

namespace App\Module\WP\Admin\Application;

use App\Module\WP\Plugin\Domain\PluginNameConstant;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class AdminMenu
{
    private static bool $loaded = false;
    private Request $request;

    public function __construct(
        private readonly AdminHandler $adminHandler
    ) {}

    public function addMenu(): void
    {
        if (!self::$loaded) {
            $this->request = Request::createFromGlobals();

            $this->action(function (string $pluginName, Response $response) {
                add_menu_page('', 'Symfony App', 'manage_options', $pluginName,
                    function () use($response) {
                        $this->adminHandler->handleResponse($response);
                    });
            });

            self::$loaded = true;
        }
    }

    private function action(callable $callable, string $pluginName = PluginNameConstant::NAME): void
    {
        add_action('admin_menu', function() use ($pluginName, $callable) {
            $response = new Response();

            if ($this->request->query->get('page') === $pluginName) {
                $response = $this->adminHandler->handleRequest();
                $this->adminHandler->handleRedirect($response);
            }

            $callable($pluginName, $response);
        });
    }
}
