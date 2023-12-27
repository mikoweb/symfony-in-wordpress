<?php

namespace App\Module\Dashboard\Application\Controller\Admin;

use App\Module\WP\Admin\Application\AdminTitle;
use App\Module\WP\Admin\Application\Controller\AbstractAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class DashboardController extends AbstractAdminController
{
    public function getDashboard(Request $request): Response
    {
        AdminTitle::setTitle('App Dashboard');

        return $this->render('admin/dashboard/dashboard.html.twig');
    }
}
