<?php

namespace app\model;

use app\interfaces\IModel;
use app\engine\App;


abstract class Repository implements IModel
{

    public  function getOne($id)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} WHERE id = :id";
        return App::call()->Db->queryOne($sql, ['id' => $id], $this->getEntityClass());
    }

    public  function getOneByField($field, $value)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} WHERE {$field} = :{$field}";
        return App::call()->Db->queryOne($sql, ["{$field}" => $value], $this->getEntityClass());
    }

    public  function getOneAND($field1, $value1, $field2, $value2)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} WHERE {$field1} = :{$field1} AND {$field2} = :{$field2}";
        return App::call()->Db->queryOne($sql, ["{$field1}" => $value1, "{$field2}" => $value2], $this->getEntityClass());
    }

    public  function getAll($fieldName = null, $value = null)
    {
        $tableName = $this->getTableName();
        $sql = $fieldName && $value ? "SELECT * FROM {$tableName} WHERE `{$fieldName}`={$value}" : "SELECT * FROM {$tableName}";
        return App::call()->Db->queryAll($sql);
    }
    public function getAllRevert($fieldName = null, $value = null)
    {
        $tableName = $this->getTableName();
        $sql = $fieldName && $value ? "SELECT * FROM {$tableName} WHERE `{$fieldName}`={$value} ORDER BY id DESC" : "SELECT * FROM {$tableName} ORDER BY id DESC";
        return App::call()->Db->queryAll($sql);
    }

    public function getLimit($from, $to)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} LIMIT ?, ?";
        return App::call()->Db->queryLimit($sql, $from, $to);
    }
    public  function getLimitRevert($from, $to)
    {
        $tableName = $this->getTableName();
        $sql = "SELECT * FROM {$tableName} ORDER BY id DESC LIMIT ?, ?";
        return App::call()->Db->queryLimit($sql, $from, $to);
    }

    public function insert(Model $entity)
    {

        $tableName = $this->getTableName();
        $data = $this->getColumnsAndValues($entity);
        $sql = "INSERT INTO {$tableName} ({$data['columns']}) VALUES ({$data['values']})";
        App::call()->Db->execute($sql, $data['params']);
        
        $entity->id = App::call()->Db->getLastId();
     
    }

    public function delete(Model $entity)
    {   
        $tableName = $this->getTableName();
        $sql = "DELETE FROM {$tableName} WHERE id=:id";
        return App::call()->Db->execute($sql, [':id' => $entity->id]);
    }

    public function update(Model $entity)
    {
        $params = [];
        $colums = [];
        foreach ($entity->getTrueProps() as $key => $val) {
            $params[":{$key}"] = $entity->$key;
            $colums[] = "`{$key}` = :{$key}";
        }
        $colums = implode(", ", $colums);
        $tableName = $this->getTableName();
        $params['id'] = $entity->id;
        $sql = "UPDATE `{$tableName}` SET {$colums} WHERE `id`=:id";
        App::call()->Db->execute($sql, $params);
        $entity->setFalseAllProps();
    }


    public  function save(Model $entity)
    {

        if (is_null($entity->id)) {
            $this->insert($entity);
        } else {
            $this->update($entity);
        }
    }

    protected function getColumnsAndValues(Model $obj)
    {
        $colums = null;
        $values = null;
        $params = [];

        foreach ($obj->showKeys() as $key => $value) {
            if ($key === "props") continue;
            $params[$key] = $value;
        }
        $keys = array_keys($params);
        $count = 0;
        foreach ($keys as $key) {
            $if = count($keys) - 1 !== $count++;
            $colums .= $if ? '`' . $key . '`, ' : '`' . $key . '`';
            $values .= $if ? ':' . "{$key}" . ', ' : ':' . "{$key}";
        }

        return ['columns' => $colums, 'values' => $values, 'params' => $params];
    }

    abstract protected function getEntityClass();

    abstract protected function getTableName();
}
