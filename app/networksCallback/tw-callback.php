<?php
if (!session_id()) {
    session_start();
}

use Bdn\Socpost\Controller;

include_once __DIR__ . '/../../config.php';
require_once HOME_DIR . '/app/autoload.php';

if (isset($_REQUEST['oauth_verifier'], $_REQUEST['oauth_token']) && $_REQUEST['oauth_token'] == $_SESSION['oauth_token']) {
    $tw = new Controller\TwitterController($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
    try {
        $accessToken = $tw->getInstance()->oauth('oauth/access_token', ['oauth_verifier' => $_REQUEST['oauth_verifier']]);
    } catch (\Abraham\TwitterOAuth\TwitterOAuthException $e) {
        echo 'Twitter error' . $e->getMessage();
        exit;
    }
}
if (isset($accessToken)) {
    $_SESSION['twitter_access_token'] = $accessToken;
}

header('Location: http://socpost.local/index.php');