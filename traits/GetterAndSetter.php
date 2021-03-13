<?php

namespace app\traits;

trait GetterAndSetter
{
    public function __get($name)
    {
        return property_exists($this, $name) ? $this->$name :  "This object doesn't has property - {$name}";
    }
    public function __set($name, $values)
    {
        if (property_exists($this, $name)) {
            $this->$name = $values;
            foreach ($this->props as $key => $prop) {
                if ($key == $name) {
                    $this->props[$key] = true;
                }
            }
        } else {
            return "This object doesn't has property - {$name}";
        }
    }

    public function __isset($name)
    {
        return isset($this->$name);
    }
}
