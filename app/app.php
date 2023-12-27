<?php

defined('WP_PLUGIN_DIR') or die;

require __DIR__ . '/vendor/autoload.php';

use App\Module\WP\Admin\Application\AdminMenu;
use App\Path;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenvArgs = [Path::getAppPath('.env')];

if (file_exists(Path::getAppPath('.env.local'))) {
    $dotenvArgs[] = Path::getAppPath('.env.local');
}

call_user_func_array([$dotenv, 'load'], $dotenvArgs);

if (is_admin()) {
    AdminMenu::addMenu();
    // TODO load admin routing
} else {
    // TODO load site routing
}
