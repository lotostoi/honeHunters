<?php 
namespace app\interfaces;

interface Irender {
    public function renderTemplate($template, $params = []);
}