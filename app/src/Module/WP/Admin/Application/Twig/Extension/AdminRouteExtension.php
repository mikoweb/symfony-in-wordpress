<?php

namespace App\Module\WP\Admin\Application\Twig\Extension;

use App\Module\WP\Admin\Application\Routing\AdminRouter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class AdminRouteExtension extends AbstractExtension
{
    public function __construct(
        private readonly AdminRouter $adminRouter,
    ) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('admin_route', [$this, 'getAdminRoute']),
            new TwigFunction('admin_link', [$this, 'getAdminLink']),
        ];
    }

    public function getAdminRoute(
        string $name,
        array $parameters = [],
        int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH
    ): string
    {
        return $this->adminRouter->generate($name, $parameters, $referenceType);
    }

    public function getAdminLink(array $queryParams = []): string
    {
        return $this->adminRouter->generateAdminLink($queryParams);
    }
}
