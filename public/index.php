<?php
require_once '../vendor/autoload.php';

$config = include '../config/config.php';

use app\engine\App;

try {
    App::getInstance()->run($config);
} catch (\Exception  $e) {
    var_dump($e);
}

