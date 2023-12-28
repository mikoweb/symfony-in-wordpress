<?php

namespace App\Module\Service;

use App\Module\WP\Site\Application\SiteHandler;

final readonly class SiteService
{
    public function __construct(
        public SiteHandler $siteHandler
    ) {}
}
