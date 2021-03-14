<?php

return [
    'namespaces_Controllers' => "app\\controllers\\",
    'templates' => $_SERVER['CONTEXT_DOCUMENT_ROOT'] . "/" . "twigViews/",
    'components' => [
        'Db' => [
            'class' => app\engine\Db::class,
            'driver' => 'mysql',
            'host' => 'localhost:3306',
            'login' => 'root',
            'password' => 'root',
            'database' => 'honehunters',
            'charset' => 'utf8'
        ],
        'Request' => ['class' => app\engine\Request::class],
        'Session' => ['class' => app\engine\Session::class],
        'MessagesRepository' => ['class' => app\model\repositories\MessagesRepository::class],
    ],
];
