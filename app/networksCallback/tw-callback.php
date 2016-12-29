<?php
if (!session_id()) {
    session_start();
}
//var_dump($_SESSION);
include_once __DIR__ . '/../../src/config.php';
include __DIR__ . '/../../src/lib/twitteroauth/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

if (isset($_REQUEST['oauth_verifier'], $_REQUEST['oauth_token']) && $_REQUEST['oauth_token'] == $_SESSION['oauth_token']) {
    $request_token = [];
    $request_token['oauth_token'] = $_SESSION['oauth_token'];
    $request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];
    $connection = new TwitterOAuth(CONSUMER_KEY_TW, CONSUMER_SECRET_TW, $request_token['oauth_token'], $request_token['oauth_token_secret']);
    $access_token = $connection->oauth('oauth/access_token', ['oauth_verifier' => $_REQUEST['oauth_verifier']]);
    $_SESSION['access_token'] = $access_token;
}
header('Location: http://socpost.local/index.php');