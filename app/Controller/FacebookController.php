<?php
namespace Bdn\Socpost\Controller;
include HOME_DIR . '/vendor/autoload.php';
use Bdn\Socpost\Core;
use Facebook;

class FacebookController extends Core\NetworksController
{
    public function __construct()
    {
        $this->instance = new Facebook\Facebook([
            'app_id' => FB_APP_ID,
            'app_secret' => FB_APP_SECRET,
            'default_graph_version' => 'v2.7']);

        $this->accessToken = isset($_SESSION['facebook_access_token']) ? $_SESSION['facebook_access_token'] : '';
        $this->instance->setDefaultAccessToken($this->accessToken);
    }

    public function getInstance()
    {
        if (is_null($this->instance)) {
            $this->instance = new Facebook\Facebook([
                'app_id' => FB_APP_ID,
                'app_secret' => FB_APP_SECRET,
                'default_graph_version' => 'v2.7']);
        }
        return $this->instance;
    }


    public function getLoginUrl()
    {
        $loginUrl = '';
        if (empty($this->getAccessToken())) {
            /**Ask user about permissions*/
            $helper = $this->instance->getRedirectLoginHelper();
            $permissions = ['email', 'publish_actions', 'publish_pages', 'manage_pages', 'user_posts']; // optional
            $loginUrl = $helper->getLoginUrl(OAUTH_CALLBACK_FB, $permissions);
        } else {
            $this->instance->setDefaultAccessToken($this->accessToken);
        }
        return $loginUrl;
    }

    public function post($msg)
    {
        if (!empty($msg)) {
            try {
                $response = $this->instance->post('/me/feed', $msg, $this->instance->getDefaultAccessToken());
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
}