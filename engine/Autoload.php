<?php

class Autoload
{
    public function loadClass($className)
    {
        $fileName = $className . ".php";
        $fileName = str_replace('\\', '/', $fileName);
        $fileName = str_replace('app', '..', $fileName);
      
        if (file_exists($fileName)) {
            include $fileName;
        }
    }
}
