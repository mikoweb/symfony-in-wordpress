<?php

namespace App\Module\WP\Site\Application;

final class SiteTitle
{
    public static function setTitle(string $title): void
    {
        add_filter('document_title_parts', function (array $titles) use($title) {
            $titles['title'] = $title;
            return $titles;
        }, 1000000);
    }
}
