<?php

namespace App\Module\WP\Admin\Application;

use App\Module\WP\Admin\Application\Routing\AdminRoutingLoader;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Twig\Environment;

final readonly class AdminHandler
{
    public function __construct(
        private AdminRoutingLoader $adminRoutingLoader,
        private Environment $twig,
    ) {}

    public function handleRequest(): Response
    {
        try {
            $response = $this->adminRoutingLoader->handleRequest();
        } catch (HttpExceptionInterface $exception) {
            $response = new Response($this->twig->render('admin/error.html.twig', [
                'statusCode' => $exception->getStatusCode(),
            ]));
        }

        return $response;
    }

    public function handleResponse(Response $response): void
    {
        if (!$response instanceof RedirectResponse) {
            echo $response->getContent();
        }
    }

    public function handleRedirect(Response $response): void
    {
        if ($response instanceof RedirectResponse) {
            wp_redirect($response->getTargetUrl());
            exit;
        }
    }
}
