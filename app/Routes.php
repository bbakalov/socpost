<?php
namespace Bdn\Socpost;
use Bdn\Socpost\Controller;

class Routes
{
    /**
     * @var array
     */
    public $urlElements;
    /**
     * @var string
     */
    public $requestMethod;
    /**
     * @var array
     */
    public $urlParameters;

    public function __construct()
    {
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        if (isset($_SERVER['PATH_INFO'])) {
            $this->urlElements = explode('/', $_SERVER['PATH_INFO']);
        } else {
            $this->urlElements = array_filter(
                explode('/', str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI'])));
        }

        $urlParameters = [];
        if (isset($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], $urlParameters);
        }
        $this->urlParameters = $urlParameters;
    }

    public function start()
    {
        if (isset($this->urlElements[1]) && isset($this->urlElements[2])) {
            $controller = ucfirst($this->urlElements[1]) . 'Controller';
            $action = $this->urlElements[2] . 'Action';
        } elseif (isset($this->urlElements[1]) && !isset($this->urlElements[2])) {
            $controller = ucfirst($this->urlElements[1]) . 'Controller';
            $action = 'indexAction';
        } else {
            $controller = 'IndexController';
            $action = 'indexAction';
        }

        if (class_exists($controller)) {
            $controller = new $controller;
        } else {
            $controller = new Controller\IndexController();
        }
        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            $controller->indexAction();
        }
    }
}
