<?php

class DbtGen_Model_DrvMySql extends DbtGen_Model_Structure
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
        while ($arrTbl = $rs->fetch(PDO::FETCH_NUM)) {
            $tableName = $arrTbl[0];
            $pkFields = $this->getPkFields($tableName);
            $tblTmp = new DbtGen_Model_Table($this->dbproj, $tableName, $pkFields);
            $this->setTableFields($tblTmp);
            $tables[] = $tblTmp;
        }
        return $tables;
    }

    public function __construct()
    {
        $this->driver = 'mysql';
    }

    private function connect()
    {
        if (self::$conn === null) {
            $connectionString = 'mysql:host=' . $this->host . ';';
            $connectionString .= 'dbname=' . $this->db . ';';
            $connectionString .= 'charset=utf8';
            self::$conn = new PDO($connectionString, $this->user, $this->passwd);
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
        $query = "SHOW FIELDS FROM $table";
        $rs = self::$conn->query($query);
        $pkFields = array();
        while ($obj = $rs->fetch(PDO::FETCH_OBJ)) {
            if ($obj->Key == 'PRI') {
                $pkFields[] = $obj->Field;
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
        $query = "SHOW TABLES";
        return self::$conn->query($query);
    }

    /**
     *
     * @param DbtGen_Model_Table $table
     */
    private function setTableFields(DbtGen_Model_Table $table)
    {
        $this->connect();
        $query = 'SHOW FIELDS FROM ' . $table->getTableName();
        $rs = self::$conn->query($query);
        while ($obj = $rs->fetch(PDO::FETCH_OBJ)) {
            $fieldTmp = new DbtGen_Model_Field($obj->Field);
            $table->addField($fieldTmp);
        }
    }
}
