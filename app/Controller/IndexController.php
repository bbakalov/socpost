<?php
namespace Bdn\Socpost\Controller;
include HOME_DIR . '/vendor/autoload.php';
use Bdn\Socpost\Core;

class IndexController extends Core\CoreController
{
    protected $facebookConnector;
    protected $twitterConnector;

    public function __construct()
    {
        parent::__construct();
        $connectors = [
            'facebook' => new FacebookController(),
            'twitter' => new TwitterController(),
        ];

        foreach (NETWORKS_INFO as $network => $status) {
            if ($status['enabled'] == true) {
                $connectorEntity = $network . 'Connector';
                $this->{$connectorEntity} = $connectors[$network];
            }
        }
    }

    public function indexAction()
    {
        $this->view->assign('fbLoginUrl', $this->facebookConnector->getLoginUrl());
        $this->view->assign('fbAccessToken', $this->facebookConnector->getAccessToken());
        $this->view->assign('twLoginUrl', $this->twitterConnector->getLoginUrl());
        $this->view->assign('twAccessToken', $this->twitterConnector->getAccessToken());
        $this->view->render('index.view.php');
    }

    public function getAccessToken()
    {
        echo __FUNCTION__;
    }


    public function postAction()
    {
        echo __FUNCTION__;
        //TODO: make one push button for sending message to social network
        if (!empty($_GET['text']) && !empty($_GET['net'])) {
            $socNetwork = $_GET['net'];
            $msg = $_GET['text'];
            switch ($socNetwork) {
                case 'facebook':
                    $msg = ['message' => (string)$msg];
                    $res = $this->facebookConnector->post($msg);
                    header('Location: http://socpost.local/index.php');
                    break;
                case 'twitter':
                    $msg = ['status' => $msg];
                    $res = $this->twitterConnector->post($msg);
                    header('Location: http://socpost.local/index.php');
                    break;
                default:
                    $res = 'switch went wrong';
                    break;
            }

            $_SESSION['msgToUser'] = "$socNetwork : $res";
            $this->indexAction();
        }
    }
}