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
include __DIR__ . '/src/lib/php-graph-sdk-5.0.0/src/Facebook/autoload.php';
include __DIR__ . '/src/lib/twitteroauth/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * Twitter auth
 */
if (!isset($_SESSION['access_token'])) {
    $twitter = new TwitterOAuth(CONSUMER_KEY_TW, CONSUMER_SECRET_TW);
    $requestToken = $twitter->oauth('oauth/request_token', ['oauth_callback' => OAUTH_CALLBACK_TW]);
    $_SESSION['oauth_token'] = $requestToken['oauth_token'];
    $_SESSION['oauth_token_secret'] = $requestToken['oauth_token_secret'];
    $url = $twitter->url('oauth/authorize', ['oauth_token' => $requestToken['oauth_token']]);
    echo "<p><a href=" . $url . ">Log in with Twitter!</a></p>";
} else {
    $twAccessToken = $_SESSION['access_token'];
    $twitter = new TwitterOAuth(CONSUMER_KEY_TW, CONSUMER_SECRET_TW, $twAccessToken['oauth_token'], $twAccessToken['oauth_token_secret']);
    $user = $twitter->get('account/verify_credentials');
//    echo '<p>Logged in with Twitter</p>';
    $twAccessToken = implode(' | ', $twAccessToken);
//    echo "<p style='font-size:smaller;'>TW AccessToken: $twAccessToken</p>";
//    $media_url = $user->status->entities->media[0]->media_url;
//    echo "<img src='$media_url' width='100' height='100'/>";
//    echo $user->status->text;
}

/**
 * Facebook auth
 */
$fb = new Facebook\Facebook(array(
    'app_id' => FB_APP_ID,
    'app_secret' => FB_APP_SECRET,
    'default_graph_version' => 'v2.7'
));

if (!isset($_SESSION['facebook_access_token'])) {
    /**Ask user about permissions*/
    $helper = $fb->getRedirectLoginHelper();
    $permissions = ['email', 'publish_actions', 'publish_pages', 'manage_pages', 'user_posts']; // optional
    $loginUrl = $helper->getLoginUrl(OAUTH_CALLBACK_FB, $permissions);
    echo '<p><a href="' . $loginUrl . '">Log in with Facebook!</a></p>';
} else {
    $fbAccessToken = $_SESSION['facebook_access_token'];
    $fb->setDefaultAccessToken($fbAccessToken);
//    echo '<p>Logged in with Facebook</p>';
//    echo "<p style='font-size:smaller;'>FB AccessToken: $fbAccessToken</p>";
}

if (!empty($_GET['text']) && !empty($_GET['net'])) {
    $socNetwork = $_GET['net'];
    $msg = $_GET['text'];
    switch ($socNetwork) {
        case 'facebook':
            $msg = ['message' => (string)$msg];
            $res = postFacebook($fb, $msg);
            break;
        case 'twitter':
            $msg = ['status' => $msg];
            $res = postTwitter($twitter, $msg);
            break;
        default:
            $res = 'switch went wrong';
            break;
    }

    $_SESSION['msgToUser'] = "$socNetwork : $res";
    header('Location: ./index.php');
}

/**
 * Add post to user wall
 * @param \Facebook\Facebook $fbConnector
 * @param array $msg
 * @return \Facebook\GraphNodes\GraphNode|string
 */
function postFacebook(Facebook\Facebook $fbConnector, array $msg)
{
    if (!empty($msg)) {
        try {
            $response = $fbConnector->post('/me/feed', $msg, $fbConnector->getDefaultAccessToken());
            $responseBody = $response->getDecodedBody();
            error_log("{$responseBody['id']}\n", 3, FB_LOG_PATH);
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        return $responseBody['id'];
    } else {
        return '';
    }
}

function postTwitter(TwitterOAuth $connector, array $msg)
{
    if (!empty($msg)) {
        try {
            $response = $connector->post('statuses/update', $msg);
            if (isset($response->errors)) {
                return $response->errors[0]->message;
            }
        } catch (\Abraham\TwitterOAuth\TwitterOAuthException $e) {
            echo 'Twitter error' . $e->getMessage();
            exit;
        }
        error_log("{$response->id}\n", 3, TW_LOG_PATH);
        return $response->id;
    } else {
        return '';
    }
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
    <?php if (isset($_SESSION['access_token'])): ?>
        <p>Logged in with Twitter</p>
        <p style='font-size:smaller;'>TW AccessToken: <?php echo $twAccessToken ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['facebook_access_token'])): ?>
        <p>Logged in with Facebook</p>
        <p style='font-size:smaller;'>FB AccessToken: <?php echo $fbAccessToken ?></p>
    <?php endif; ?>
</div>
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

</body>
</html>
