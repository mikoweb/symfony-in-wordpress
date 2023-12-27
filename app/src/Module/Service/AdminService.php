<?php

namespace App\Module\Service;

use App\Module\WP\Admin\Application\AdminMenu;

final readonly class AdminService
{
    public function __construct(
        public AdminMenu $adminMenu
    ) {}
}
