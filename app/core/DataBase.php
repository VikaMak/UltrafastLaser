<?php

class DataBase
{

    private static $instance = null;
    
    public $pdo;

    private $db_error = false;
    private $db_query;
    private $db_result;
    private $db_count = 0;
    private $db_lastid;

    private $host;
    private $user;
    private $password;
    private $db_name;    
    private $dsb;
    

    private function __construct()
    {        
        /**
         * Соединение с БД
         * @var object
         */
        $this->host = Config::getValue('mysql/host');
        $this->user = Config::getValue('mysql/user');
        $this->password = Config::getValue('mysql/password');
        $this->db_name = Config::getValue('mysql/name');
        $this->dsb = 'mysql:dbname='.$this->db_name.';host='.$this->host;

        try {
            $this->pdo = new PDO($this->dsb, Config::getValue('mysql/user'), Config::getValue('mysql/password'));
        } catch (PDOException $e) {
            echo 'Failed to connect to DataBase: '.$e->getMessage();
        }

    }

    private function __clone()
    {
        
    }

    private function __wakeup()
    {
        
    }

    public static function getInstance()
    {
        if(self::$instance == null) {

            self::$instance = new self();
        }

        return self::$instance;
    }

   public function query($sql, $params = [])
    {
        $this->db_error = false;

        if ($this->db_query = $this->pdo->prepare($sql)) {
            $i = 1;
            if (count($params)) {
                foreach ($params as $param) {
                    $this->db_query->bindValue($x, $param);
                    $i++;
                }
            }

            if ($this->db_query->execute()) {
                $this->db_result = $this->db_query->fetchAll(PDO::FETCH_OBJ);
                $this->db_count = $this->db_query->rowCount();
                $this->db_lastid = $this->pdo->lastInsertId();
            } else {
                $this->db_error = true;
            }
        }

        return $this;
    }

    
    public function results()
    {
        return $this->db_result;
    }
    
    public function count()
    {
        return $this->db_count;
    }

    public function lastId()
    {
        return $this->db_lastid;
    }

    public function error()
    {
        return $this->db_error;
    }
}