<?php
namespace Bdn\Socpost\Core;

abstract class NetworksController
{
    protected $instance;
    protected $accessToken;

    public function getInstance()
    {
        return $this->instance;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    abstract public function getLoginUrl();

    abstract public function post($msg);
}