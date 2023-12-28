<?php

namespace App\Module\LoremIpsum\Application\Controller;

use App\Module\WP\Site\Application\Controller\AbstractSiteController;
use App\Module\WP\Site\Application\SiteTitle;
use Symfony\Component\HttpFoundation\Response;

final class LoremIpsumController extends AbstractSiteController
{
    public function index(): Response
    {
        SiteTitle::setTitle('Lorem Ipsum');

        return $this->render('site/lorem_ipsum.html.twig');
    }
}
