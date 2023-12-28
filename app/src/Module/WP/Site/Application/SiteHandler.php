<?php

namespace App\Module\WP\Site\Application;

use App\Module\WP\Site\Application\Routing\SiteRoutingLoader;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use WP_Post;
use WP;

final readonly class SiteHandler
{
    private const string ROUTER_PAGE_PATH = '/symfony-application-router-page/';

    public function __construct(
        public readonly SiteRoutingLoader $siteRoutingLoader
    ) {}

    public function handle(): void
    {
        if ($this->isSiteRoute()) {
            $response = $this->siteRoutingLoader->handleRequest();
            $oldUri = $_SERVER['REQUEST_URI'];

            add_action('init', function() use ($response) {
                $this->preparePage();

                if ($response instanceof RedirectResponse) {
                    wp_redirect($response->getTargetUrl());
                    exit();
                }
            });

            add_action('do_parse_request', function (bool $parse) {
                $_SERVER['REQUEST_URI'] = self::ROUTER_PAGE_PATH;
                return $parse;
            });

            add_action('parse_request', function (WP $wp) use($oldUri) {
                $_SERVER['REQUEST_URI'] = $oldUri;
                $wp->request = $oldUri;
            });

            add_filter('the_title', function() {
                return '';
            });

            add_filter('the_content', function () use($response) {
                if (!$response instanceof RedirectResponse) {
                    return $response->getContent();
                }

                return '';
            });
        }
    }

    private function isSiteRoute(): bool
    {
        if (!is_admin()) {
            $request = $this->siteRoutingLoader->getRequest();
            $urlMatcher = $this->siteRoutingLoader->getMatcher();

            try {
                $matches = $urlMatcher->match($request->getPathInfo());

                return !empty($matches);
            } catch (ResourceNotFoundException $exception) {}
        }

        return false;
    }

    private function preparePage(): WP_Post
    {
        $page = get_page_by_path(self::ROUTER_PAGE_PATH);

        if (is_null($page)) {
            wp_insert_post([
                'post_name' => self::ROUTER_PAGE_PATH,
                'post_title' => self::ROUTER_PAGE_PATH,
                'post_content' => '',
                'post_status' => 'draft',
                'post_type' => 'page'
            ]);

            $page = get_page_by_path(self::ROUTER_PAGE_PATH);
        }

        return $page;
    }
}
