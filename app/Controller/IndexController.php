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
        if (!empty($_GET['text']) && !empty($_GET['networks'])) {
            $res = [];
            $socNetworks = $_GET['networks'];
            $msg = $_GET['text'];
            foreach ($socNetworks as $network) {
                $result = $this->{"{$network}Connector"}->post($msg);
                $res[$network] = $result;
            }
            $_SESSION['msgToUser'] = $res;
        } else {
            $_SESSION['msgToUser'] = ['General' => 'Please Logging in to social networks OR choose one of networks for posting!'];
        }
        header('Location: http://socpost.local/index.php');
    }
}