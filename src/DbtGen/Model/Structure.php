<?php

abstract class DbtGen_Model_Structure
{

    protected $driver;

    protected $user;

    protected $passwd;

    protected $db;

    protected $host;

    protected $dbproj;

    /**
     * Retorna tabelas disponiveis no banco de dados
     *
     * @return DbtGen_Model_Table[]
     */
    public abstract function getTables();

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function setPasswd($passwd)
    {
        $this->passwd = $passwd;
        return $this;
    }

    public function setDb($db)
    {
        $this->db = $db;
        return $this;
    }

    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    public function setDbproj($dbproj)
    {
        $this->dbproj = $dbproj;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getPasswd()
    {
        return $this->passwd;
    }

    public function getDb()
    {
        return $this->db;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getDbproj()
    {
        return $this->dbproj;
    }

    public function getDriver()
    {
        return $this->driver;
    }
}
