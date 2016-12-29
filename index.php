<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if (!session_id()) {
    session_start();
}
//var_dump($_SESSION);
//session_destroy(); die;

include_once __DIR__ . '/src/config.php';
require_once __DIR__ . '/src/lib/php-graph-sdk-5.0.0/src/Facebook/autoload.php';
require_once __DIR__ . '/src/lib/twitteroauth/autoload.php';
require_once __DIR__ . '/app/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;

$networks = [
    'facebook' => new Facebook\Facebook(['app_id' => FB_APP_ID, 'app_secret' => FB_APP_SECRET, 'default_graph_version' => 'v2.7']),
    'twitter' => new TwitterOAuth(CONSUMER_KEY_TW, CONSUMER_SECRET_TW),
];

$indexController = new IndexController($networks);
$twAccessToken = $indexController->getTwitterAccessToken();
$fbAccessToken = $indexController->getFacebookAccessToken();
$indexController->getTwitterFront($twAccessToken);
$indexController->getFacebookFront($fbAccessToken);

if (!empty($_GET['text']) && !empty($_GET['net'])) {
    $socNetwork = $_GET['net'];
    $msg = $_GET['text'];
    switch ($socNetwork) {
        case 'facebook':
            $msg = ['message' => (string)$msg];
            $res = $indexController->postFacebook($msg);
            header('Location: http://socpost.local/index.php');
            break;
        case 'twitter':
            $msg = ['status' => $msg];
            $res = $indexController->postTwitter($msg);
            header('Location: http://socpost.local/index.php');
            break;
        default:
            $res = 'switch went wrong';
            break;
    }

    $_SESSION['msgToUser'] = "$socNetwork : $res";
    header('Location: http://socpost.local/index.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="shortcut icon" type="image/png" href="favicon.ico"/>
</head>
<body>
<div>
    <?php if (isset($_SESSION['msgToUser'])): ?>
        <p><?php echo $_SESSION['msgToUser']; ?></p>
    <?php endif; ?>

</div>
<div>
    <?php if (!empty($twAccessToken)): ?>
        <p>Logged in with Twitter</p>
        <p style='font-size:smaller;'>TW AccessToken: <?php echo implode(' | ', $twAccessToken) ?></p>
    <?php endif; ?>
    <?php if (!empty($fbAccessToken)): ?>
        <p>Logged in with Facebook</p>
        <p style='font-size:smaller;'>FB AccessToken: <?php echo $fbAccessToken ?></p>
    <?php endif; ?>
</div>
<div>
    Post to Facebook:
    <form name="postFb" action="index.php" method="GET">
        <input type="text" name="text" value="TestText<?php echo time() ?>">
        <input type="hidden" name="net" value="facebook">
        <br>
        <input type="submit" value="PostFb">
    </form>
    Post to Twitter:
    <form name="postTw" action="index.php" method="GET">
        <input type="text" name="text" value="TestText<?php echo time() ?>">
        <input type="hidden" name="net" value="twitter">
        <br>
        <input type="submit" value="PostTw">
    </form>
</div>
</body>
</html>
