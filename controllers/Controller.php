<?php

namespace app\controllers;


use app\interfaces\Irender;
use app\engine\App;

class Controller
{
    protected $defaultAction;
    protected $action;
    public $params = [];

    protected $renderer;

    public function __construct(Irender $renderer)
    {
        $this->defaultAction = 'main';
        $this->renderer = $renderer;
    }
    public function runAction($action = null)
    {
        $this->action = $action ?: $this->defaultAction;
        $method = "action" . ucfirst($this->action);
        if (method_exists($this, $method)) {
            $this->$method();
        } else {
            echo $this->render('p404', ['message' => App::call()->Request->getRequestString()]);
        }
    }

    public function render($template, $params = [])
    {
        return $this->renderTemplate($template, $params);
    }

    public function renderTemplate($template, $params = [])
    {
        return $this->renderer->renderTemplate($template, $params);
    }
}
