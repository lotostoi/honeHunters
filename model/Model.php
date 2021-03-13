<?php

namespace app\model;

use app\traits\GetterAndSetter;

abstract class Model
{
    use GetterAndSetter;

    protected $props = null;

    public function __construct()
    {
        $this->props = $this->setProps();
    }

    public function setProps()
    {
        $props = [];
        foreach (get_object_vars($this) as $key => $value) {
            if ($key == 'props') continue;
            $props["$key"] = false;
        }
        return $props;
    }

    public function getTrueProps()
    {
        $props = [];
        foreach ($this->props as $key => $value) {
            if ($value) {
                $props["$key"] = $value;
            }
        }
        return $props;
    }

    public function setFalseAllProps()
    {
        foreach ($this->props as $key => $value) {
            $this->props[$key] = false;
        }
    }
    public function showKeys()
    {
        $keys = [];
        foreach (get_object_vars($this) as $key => $value) {
            if ($key == 'props') continue;
            $keys[$key] = $value;
        }
        return $keys;
    }
}
