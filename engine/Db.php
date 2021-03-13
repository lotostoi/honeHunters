<?php

namespace app\engine;
class Db
{
    protected $connection =  null;
    protected $config = null;


    public function __construct($driver = null, $host = null, $login = null, $password = null, $database = null, $charset = "utf8")
    {
        $this->config['driver'] = $driver;
        $this->config['host'] = $host;
        $this->config['login'] = $login;
        $this->config['password'] = $password;
        $this->config['database'] = $database;
        $this->config['charset'] = $charset;
    }


    public function getConnection()
    {
        if (is_null($this->connection)) {
            $this->connection = new \PDO(
                $this->prepareDsnString(),
                $this->config['login'],
                $this->config['password']
            );
            $this->connection->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        }
        return $this->connection;
    }

    private function prepareDsnString()
    {
  
        return sprintf(
            "%s:host=%s;dbname=%s",
            $this->config['driver'],
            $this->config['host'],
            $this->config['database']
        );
    }

    protected function query($sql, $params)
    {

        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }



    public function queryOne($sql, $params, $class = null)
    {
        if (!$class) {
            return $this->query($sql, $params)->fetch();
        } else {
            $stmt = $this->query($sql, $params);
            $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $class);
            return $stmt->fetch();
        }
    }

    public function queryLimit($sql, $from, $to)
    {
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->bindParam(1, $from, \PDO::PARAM_INT);
        $stmt->bindParam(2, $to, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function queryAll($sql, $params = [])
    {
        return $this->query($sql, $params)->fetchAll();
    }



    public function execute($sql, $params = [])
    {
        return $this->query($sql, $params)->rowCount();
    }

    public function getLastId()
    {
        return $this->getConnection()->lastInsertId();
    }
}
