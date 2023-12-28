<?php

namespace App\Module\Service;

use App\Module\Core\Application\Translator\TranslatorLoader;
use App\Module\Core\Application\Twig\TwigLoader;
use App\Module\WP\Admin\Application\Routing\AdminRoutingLoader;
use App\Module\WP\Site\Application\Routing\SiteRoutingLoader;

readonly class BootstrapService
{
    public function __construct(
        private TranslatorLoader $translatorLoader,
        private AdminRoutingLoader $adminRoutingLoader,
        private TwigLoader $twigLoader,
        private SiteRoutingLoader $siteRoutingLoader
    ) {}

    public function bootstrap(): void
    {
        $this->translatorLoader->load();
        $this->adminRoutingLoader->load();
        $this->twigLoader->load();
        $this->siteRoutingLoader->load();
    }
}
