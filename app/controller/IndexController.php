<?php
use Abraham\TwitterOAuth\TwitterOAuth;

class IndexController
{

    protected $facebookConnector;
    protected $twitterConnector;
    protected $request;

    function __construct(array $networks)
    {
        $this->request = new RequestController();
        foreach ($networks as $network => $connector) {
            $connectorEntity = $network . 'Connector';
            $this->{$connectorEntity} = $connector;
        }
    }

    public function getTwitterFront($accessToken)
    {
        /**
         * Twitter auth
         */
        if (empty($accessToken)) {
            $requestToken = $this->twitterConnector->oauth('oauth/request_token', ['oauth_callback' => OAUTH_CALLBACK_TW]);
            $_SESSION['oauth_token'] = $requestToken['oauth_token'];
            $_SESSION['oauth_token_secret'] = $requestToken['oauth_token_secret'];
            $url = $this->twitterConnector->url('oauth/authorize', ['oauth_token' => $requestToken['oauth_token']]);
            echo "<p><a href=" . $url . ">Log in with Twitter!</a></p>";
        }
    }

    public function getTwitterAccessToken()
    {
        if (!isset($_SESSION['access_token'])) {
            return '';
        } else {
            $twAccessToken = $_SESSION['access_token'];
            $this->twitterConnector = new TwitterOAuth(CONSUMER_KEY_TW, CONSUMER_SECRET_TW, $twAccessToken['oauth_token'], $twAccessToken['oauth_token_secret']);
            return $twAccessToken;
        }
    }

    public function getFacebookAccessToken()
    {
        if (!isset($_SESSION['facebook_access_token'])) {
            return '';
        } else {
            return $_SESSION['facebook_access_token'];
        }
    }

    public function getFacebookFront($accessToken)
    {
        if (empty($accessToken)) {
            /**Ask user about permissions*/
            $helper = $this->facebookConnector->getRedirectLoginHelper();
            $permissions = ['email', 'publish_actions', 'publish_pages', 'manage_pages', 'user_posts']; // optional
            $loginUrl = $helper->getLoginUrl(OAUTH_CALLBACK_FB, $permissions);
            echo '<p><a href="' . $loginUrl . '">Log in with Facebook!</a></p>';
        } else {
            $this->facebookConnector->setDefaultAccessToken($this->getFacebookAccessToken());
        }
    }

    /**
     * Add post to user wall
     * @param array $msg
     * @return string
     */
    public function postFacebook(array $msg)
    {
        if (!empty($msg)) {
            try {
                $response = $this->facebookConnector->post('/me/feed', $msg, $this->facebookConnector->getDefaultAccessToken());
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

    public function postTwitter(array $msg)
    {
        if (!empty($msg)) {
            try {
                $response = $this->twitterConnector->post('statuses/update', $msg);
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
}