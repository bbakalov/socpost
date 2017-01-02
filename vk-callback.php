<?php
include_once __DIR__ . '/config.php';
include HOME_DIR . '/vendor/autoload.php';

if (!session_id()) {
    session_start();
}

$appId = '5802954';
$apiSecret = 'SzQgFBY5MKeaPkwDtVu8';
$vk = new VK\VK($appId, $apiSecret);
if (isset($_GET['code'])) {
    $_SESSION['vk_access_token'] = $vk->getAccessToken($_GET['code'], 'http://socpost.local/vk-callback.php');
}
header('Location: http://socpost.local/vk.php');