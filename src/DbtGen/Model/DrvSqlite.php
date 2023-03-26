<?php

class DbtGen_Model_DrvSqlite extends DbtGen_Model_Structure
{

    private static $conn = null;

    /**
     * Retorna tabelas
     *
     * @return DbtGen_Model_Table[]
     */
    public function getTables()
    {
        $tables = array();
        $rs = $this->tablesRs();
        while ($obj = $rs->fetch(PDO::FETCH_OBJ)) {
            $tableName = $obj->tbl_name;
            $pkFields = $this->getPkFields($tableName);
            $tblTmp = new DbtGen_Model_Table($this->dbproj, $tableName, $pkFields);
            $this->setTableFields($tblTmp);
            $tables[] = $tblTmp;
        }
        return $tables;
    }

    public function __construct()
    {
        $this->driver = 'pdo_sqlite';
    }

    private function connect()
    {
        if (self::$conn === null) {
            $connectionString = 'sqlite:' . $this->db;
            self::$conn = new PDO($connectionString, null, null);
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    /**
     *
     * @param string $table
     * @return string[]
     */
    private function getPkFields($table)
    {
        $this->connect();
        $query = "PRAGMA table_info($table)";
        $rs = self::$conn->query($query);
        $pkFields = array();
        while ($obj = $rs->fetch(PDO::FETCH_OBJ)) {
            if ($obj->pk == 1) {
                $pkFields[] = $obj->name;
            }
        }
        return $pkFields;
    }

    /**
     *
     * @return resource
     */
    private function tablesRs()
    {
        $this->connect();
        $query = "SELECT name,tbl_name 
			FROM sqlite_master 
			WHERE type = 'table' and tbl_name != 'sqlite_sequence'";
        return self::$conn->query($query);
    }

    /**
     *
     * @param DbtGen_Model_Table $table
     */
    private function setTableFields(DbtGen_Model_Table $table)
    {
        $this->connect();
        $query = 'PRAGMA table_info(' . $table->getTableName() . ')';
        $rs = self::$conn->query($query);
        while ($obj = $rs->fetch(PDO::FETCH_OBJ)) {
            $fieldTmp = new DbtGen_Model_Field($obj->name);
            $table->addField($fieldTmp);
        }
    }

    public function getHost()
    {
        return '';
    }

    public function getUser()
    {
        return '';
    }

    public function getPasswd()
    {
        return '';
    }
}
