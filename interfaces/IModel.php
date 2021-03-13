<?php

namespace app\interfaces;

interface IModel
{
    public  function getOne($id);
    public  function getAll();
    // abstract protected function getTableName();
}
