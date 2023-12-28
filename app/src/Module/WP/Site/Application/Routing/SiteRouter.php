<?php

namespace App\Module\WP\Site\Application\Routing;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouterInterface;

final class SiteRouter implements UrlGeneratorInterface
{
    private ?RouterInterface $router = null;

    public function __construct(
        private readonly SiteRoutingLoader $siteRoutingLoader
    ) {}

    public function generate(string $name, array $parameters = [], int $referenceType = self::ABSOLUTE_PATH): string
    {
        return $this->getRouter()->generate($name, $parameters, $referenceType);
    }

    public function setContext(RequestContext $context): void
    {
        $this->getRouter()->setContext($context);
    }

    public function getContext(): RequestContext
    {
        return $this->getRouter()->getContext();
    }

    private function getRouter(): RouterInterface
    {
        if (is_null($this->router)) {
            $this->router = $this->siteRoutingLoader->load();
        }

        return $this->router;
    }
}
