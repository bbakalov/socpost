<?php
include_once __DIR__ . '/../../src/config.php';
include __DIR__ . '/../../src/lib/php-graph-sdk-5.0.0/src/Facebook/autoload.php';

if (!session_id()) {
    session_start();
}

$fb = new Facebook\Facebook(array(
    'app_id' => FB_APP_ID,
    'app_secret' => FB_APP_SECRET,
    'default_graph_version' => 'v2.7'
));
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