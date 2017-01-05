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
define('HOME_DIR', '/full/path/to/your/project/');
//Facebook
define('FB_APP_ID', 'FB_APP_ID');
define('FB_APP_SECRET', 'FB_APP_SECRET');
define('OAUTH_CALLBACK_FB', '/path/to/file/with/callback/fb-callback.php');
define('FB_LOG_PATH', '/path/to/your/logs/facebook.log');
//Twitter
define('CONSUMER_KEY_TW', 'CONSUMER_KEY_TW');
define('CONSUMER_SECRET_TW', 'CONSUMER_SECRET_TW');
define('OAUTH_CALLBACK_TW', '/path/to/file/with/callback/tw-callback.php');
define('TW_LOG_PATH', '/path/to/your/logs/twitter.log');
/**
 * Here you should add connected networks and sets them status
 */
$socNetStatus = [
    'facebook' => ['enabled' => true],
    'twitter' => ['enabled' => true],
];
define('NETWORKS_INFO', $socNetStatus);
