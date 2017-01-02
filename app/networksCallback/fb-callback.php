<?php
include_once __DIR__ . '/../../config.php';
require_once HOME_DIR . '/app/autoload.php';

if (!session_id()) {
    session_start();
}

$fb = new IndexController(['facebook']);
$fb = $fb->getFacebookConnector();
$helper = $fb->getRedirectLoginHelper();
try {
    $accessToken = $helper->getAccessToken();
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

if (isset($accessToken)) {
    $_SESSION['facebook_access_token'] = (string)$accessToken;
}

header('Location: http://socpost.local/index.php');