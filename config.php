<?php
/**
 * Errors setting
 */
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
/**
 * Block of constants
 */
define('NEWS_URL', 'https://lenta.ru/rss');
define('HOME_DIR', '/var/www/nginx/socpost.local/');
//Facebook
define('FB_APP_ID', '1756621947956822');
define('FB_APP_SECRET', '3c14496989b120cf765a3d7f084c9e02');
define('OAUTH_CALLBACK_FB', 'http://socpost.local/app/networksCallback/fb-callback.php');
define('FB_LOG_PATH', '/var/www/nginx/socpost.local/logs/facebook.log');
//Twitter
define('CONSUMER_KEY_TW', '7RJAtZ9xwl46Izdp2i2y1aDzv');
define('CONSUMER_SECRET_TW', 'SgOLdq3tnj9B3LQWnCTeOQyDtfONuQvunzI7FciMfzzEz8OpGd');
define('OAUTH_CALLBACK_TW', 'http://socpost.local/app/networksCallback/tw-callback.php');
define('TW_LOG_PATH', '/var/www/nginx/socpost.local/logs/twitter.log');


$socNetStatus = [
    'facebook' => ['enabled' => true],
    'twitter' => ['enabled' => true],
];

define('NETWORKS_INFO', $socNetStatus);
