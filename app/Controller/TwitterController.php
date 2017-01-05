<?php
namespace Bdn\Socpost\Controller;
include HOME_DIR . '/vendor/autoload.php';
use Bdn\Socpost\Core;
use Abraham\TwitterOAuth;

class TwitterController extends Core\NetworksController
{
    public function __construct($oauthToken = null, $oauthTokenSecret = null)
    {
        $this->accessToken = isset($_SESSION['twitter_access_token']) ? $_SESSION['twitter_access_token'] : '';
        if (empty($oauthToken) && empty($oauthTokenSecret) && empty($this->accessToken)) {
            $this->instance = new TwitterOAuth\TwitterOAuth(CONSUMER_KEY_TW, CONSUMER_SECRET_TW);
        } else {
            if (!empty($this->accessToken)) {
                $oauthToken = $this->accessToken['oauth_token'];
                $oauthTokenSecret = $this->accessToken['oauth_token_secret'];
            }
            $this->instance = new TwitterOAuth\TwitterOAuth(CONSUMER_KEY_TW, CONSUMER_SECRET_TW, $oauthToken, $oauthTokenSecret);
            $this->instance->setOauthToken($oauthToken, $oauthTokenSecret);
        }
    }

    public function getInstance()
    {
        if (is_null($this->instance)) {
            $this->instance = new TwitterOAuth\TwitterOAuth(CONSUMER_KEY_TW, CONSUMER_SECRET_TW);
        }
        return $this->instance;
    }

    public function getLoginUrl()
    {
        $loginUrl = '';
        if (empty($accessToken)) {
            $requestToken = $this->instance->oauth('oauth/request_token', ['oauth_callback' => OAUTH_CALLBACK_TW]);
            $_SESSION['oauth_token'] = $requestToken['oauth_token'];
            $_SESSION['oauth_token_secret'] = $requestToken['oauth_token_secret'];
            error_log("{$requestToken['oauth_token']}\n", 3, TW_LOG_PATH);
            $loginUrl = $this->instance->url('oauth/authorize', ['oauth_token' => $requestToken['oauth_token']]);
        }
        return $loginUrl;
    }

    public function post($msg)
    {
        if (!empty($msg)) {
            $msg = ['status' => $msg];
            try {
                $response = $this->instance->post('statuses/update', $msg);
                if (isset($response->errors)) {
                    return $response->errors[0]->message;
                }
            } catch (TwitterOAuth\TwitterOAuthException $e) {
                echo 'Twitter error' . $e->getMessage();
                exit;
            }
            error_log("{$response->id}\n", 3, TW_LOG_PATH);
            return $response->id;
        } else {
            return '';
        }
    }
}