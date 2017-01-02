<?php
if (!session_id()) {
    session_start();
}
//var_dump($_SESSION);
include_once __DIR__ . '/../../config.php';
require_once HOME_DIR . '/app/autoload.php';

if (isset($_REQUEST['oauth_verifier'], $_REQUEST['oauth_token']) && $_REQUEST['oauth_token'] == $_SESSION['oauth_token']) {
    $tw = new IndexController(['twitter']);
    $tw = $tw->getTwitterConnector($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
    $access_token = $tw->oauth('oauth/access_token', ['oauth_verifier' => $_REQUEST['oauth_verifier']]);
    $_SESSION['access_token'] = $access_token;
}
header('Location: http://socpost.local/index.php');