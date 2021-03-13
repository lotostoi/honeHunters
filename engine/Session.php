<?php

namespace app\engine;

class Session
{

    public function sessionStart()
    {
        session_start();
    }

    public function destroySession()
    {
        session_destroy();
    }

    public function regenerateSession()
    {
        session_regenerate_id();
    }

    public function setSession($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function getSession($key)
    {
        return $_SESSION[$key];
    }
}
