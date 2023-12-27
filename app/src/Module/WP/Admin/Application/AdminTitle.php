<?php

namespace App\Module\WP\Admin\Application;

final class AdminTitle
{
    public static function setTitle(string $title): void
    {
        add_filter('admin_title', function (string $adminTitle) use($title) {
            return trim($title . ' ' . $adminTitle);
        });
    }
}
