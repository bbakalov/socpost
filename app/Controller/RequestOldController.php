<?php
namespace Bdn\Socpost\Controller;

class RequestOldController
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

    /**
     * @var bool
     */

    public function __construct()
    {
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        echo '<pre>';
//        var_dump($_SESSION);
//        var_dump($_SERVER);
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
//        var_dump($this->urlElements);
//        $this->parseIncomingParams();
//        var_dump($this);
//        return true;
    }

    /**
     * Parse incoming urlParameters
     */
    public function parseIncomingParams()
    {
        $urlParameters = [];
        if (isset($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], $urlParameters);
        }
        $body = file_get_contents("php://input");
        $contentType = false;
        if (isset($_SERVER['CONTENT_TYPE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'];
        }
        $bodyParams = (!empty($body)) ? json_decode($body) : false;
        if ($contentType == 'application/json' && !is_null($bodyParams)) {
            $urlParameters = json_decode($body, true);
            $this->isJson = true;
        } else {
            $this->isJson = false;
        }
        $this->urlParameters = $urlParameters;
    }
}