<?php

namespace app\engine;

use app\traits\TSingletone;
use ReflectionClass;

class App
{
    protected $config;
    protected $components;

    use TSingletone;

    public function run($config = [])
    {
        $this->config = $config;
        $this->components = new Storage();
        $this->Session->sessionStart();
        $this->runController();
    }
    public function __get($name)
    {
        if (stripos('config', $name) === 0) {
            return $this->$name;
        } else {
            return $this->components->get($name);
        }
    }

    public static function call()
    {
        return static::getInstance();
    }
    public function createComponent($name)
    {

        $component = $this->config['components'][$name];

        if (isset($component)) {
            $params = $component;
            $class = $params['class'];
            if (class_exists($class)) {
                unset($params['class']);
                $reflection = new ReflectionClass($class);
                return $reflection->newInstanceArgs($params);
            }
        }
        die("Component {$name} isn't in sistem...");
    }
    public function runController()
    {
        $controllerName =  $this->Request->getControllerName() ?: 'index';
        $actionName = $this->Request->getActionName();
        $controllerClass = $this->config['namespaces_Controllers'] . ucfirst($controllerName) . "Controller";
        if (class_exists($controllerClass)) {
            $controller = new $controllerClass(new TwigRender());
            $controller->runAction($actionName);
        } else {
            $controllerName =  'p404';
            $actionName = $this->Request->getActionName();
            $controllerClass = $this->config['namespaces_Controllers'] . ucfirst($controllerName) . "Controller";
            $controller = new $controllerClass(new TwigRender());
            $controller->runAction($actionName);
        }
    }
}
