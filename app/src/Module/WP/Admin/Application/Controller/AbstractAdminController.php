<?php

namespace App\Module\WP\Admin\Application\Controller;

use App\Module\Core\Application\Controller\AbstractAppController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class AbstractAdminController extends AbstractAppController
{
    protected function generateUrl(string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        return $this->container->get('admin_router')->generate($route, $parameters, $referenceType);
    }
}
