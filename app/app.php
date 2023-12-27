<?php

defined('WP_PLUGIN_DIR') or die;

require __DIR__ . '/vendor/autoload.php';

use App\Container;
use App\Path;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenvArgs = [Path::getAppPath('.env')];

if (file_exists(Path::getAppPath('.env.local'))) {
    $dotenvArgs[] = Path::getAppPath('.env.local');
}

call_user_func_array([$dotenv, 'load'], $dotenvArgs);

Container::get()->getService('bootstrap')->bootstrap();

if (is_admin()) {
    Container::get()->getService('admin')->adminMenu->addMenu();
} else {
    // TODO load site routing
}
