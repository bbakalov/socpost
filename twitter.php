<?php
session_start();
var_dump($_SESSION);
//session_destroy();die;
include_once __DIR__ . '/config.php';
include HOME_DIR . '/vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', '7RJAtZ9xwl46Izdp2i2y1aDzv');
define('CONSUMER_SECRET', 'SgOLdq3tnj9B3LQWnCTeOQyDtfONuQvunzI7FciMfzzEz8OpGd');
define('OAUTH_CALLBACK', 'http://bdn.local/socialnetworks/callback.php');

if (!isset($_SESSION['access_token'])) {
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
    $requestToken = $connection->oauth('oauth/request_token', ['oauth_callback' => OAUTH_CALLBACK]);
    $_SESSION['oauth_token'] = $requestToken['oauth_token'];
    $_SESSION['oauth_token_secret'] = $requestToken['oauth_token_secret'];
    $url = $connection->url('oauth/authorize', ['oauth_token' => $requestToken['oauth_token']]);
    $href = "<a href=" . $url . ">Авторизация twitter</a>";
    echo $href;
} else {
    $access_token = $_SESSION['access_token'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
    $user = $connection->get('account/verify_credentials');
    $media_url = $user->status->entities->media[0]->media_url;
    echo "<img src='$media_url' width='100' height='100'/>";
    echo $user->status->text;
}