<?php

namespace app\engine;

class Request
{
    protected $requestString;
    protected $controllerName;
    protected $actionName;
    protected $params;
    protected $methods;
    public function __construct()
    {
        $this->parseRequest();
    }
    private function parseRequest()
    {
        $this->requestString = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];

        $url = explode('/', $this->requestString);

        $this->controllerName = $url[1];
        if (strstr($this->controllerName, '?')) {
            $this->controllerName =  explode('?', $this->controllerName)[0];
        }

        $this->actionName = $url[2];
        if (strstr($this->actionName, '?')) {
            $this->actionName =  explode('?', $this->actionName)[0];
        }
      

        $this->params = $_REQUEST;

        $data =  json_decode(file_get_contents('php://input'));
        if (!is_null($data)) {
            foreach ($data as $key => $value) {
                $this->params[$key] = $value;
            }
        }
    }

    public function getRequestString()
    {
        return $this->requestString;
    }
    public function getControllerName()
    {
        return $this->controllerName;
    }
    public function getActionName()
    {
        return $this->actionName;
    }
    public function getParams()
    {
        return $this->params;
    }
    public function getMethod()
    {
        return $this->method;
    }
}
