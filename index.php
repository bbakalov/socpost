<?php
if (!session_id()) {
    session_start();
}
require_once __DIR__ . '/config.php';
require_once HOME_DIR . '/app/autoload.php';
//session_destroy();
$routes = new \Bdn\Socpost\Routes();
$routes->start();