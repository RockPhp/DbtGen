<?php

class DbtGen_Model_DrvOci8 extends DbtGen_Model_Structure
{

    private static $conn = null;

    private $stmt;

    /**
     * Retorna tabelas
     *
     * @return DbtGen_Model_Table[]
     */
    public function getTables()
    {
        $tables = array();
        $rs = $this->tablesRs();
        while (($obj = oci_fetch_object($this->stmt)) != false) {
            $pkFields = $this->getPkFields($obj->TABLE_NAME);
            $tblTmp = new DbtGen_Model_Table($this->dbproj, $obj->TABLE_NAME, $pkFields);
            $this->setTableFields($tblTmp);
            $tables[] = $tblTmp;
        }
        oci_free_statement($this->stmt);
        return $tables;
    }

    public function __construct()
    {
        $this->driver = 'oci8';
    }

    private function connect()
    {
        if (self::$conn === null) {
            self::$conn = oci_connect($this->user, $this->passwd, '//' . $this->host . '/' . $this->db);
        }
    }

    /**
     *
     * @param string $table
     * @return string[]
     */
    private function getPkFields($table)
    {
        $query = "SELECT cols.column_name
            FROM all_constraints cons, all_cons_columns cols
            WHERE cols.table_name = '$table'
            AND cons.constraint_type = 'P'
            AND cons.constraint_name = cols.constraint_name
            AND cons.owner = cols.owner
            AND cons.owner = '" . $this->user . "'
            AND cons.status = 'ENABLED'
            ORDER BY cols.table_name, cols.position";
        $stmt = oci_parse(self::$conn, $query);
        oci_execute($stmt);
        $pkFields = array();
        while (($obj = oci_fetch_object($stmt)) != false) {
            $pkFields[] = $obj->COLUMN_NAME;
        }
        oci_free_statement($stmt);
        return $pkFields;
    }

    /**
     *
     * @return resource
     */
    private function tablesRs()
    {
        $this->connect();
        $query = "SELECT table_name
            FROM all_tables
            where owner = '" . $this->user . "'
            ORDER BY TABLE_NAME";
        $this->stmt = oci_parse(self::$conn, $query);
        oci_execute($this->stmt);
    }

    /**
     *
     * @param DbtGen_Model_Table $table
     */
    private function setTableFields(DbtGen_Model_Table $table)
    {
        $this->connect();
        $query = "SELECT column_name
            FROM user_tab_cols
            WHERE table_name = '" . $table->getTableName() . "'";
        $stmt = oci_parse(self::$conn, $query);
        oci_execute($stmt);
        while (($obj = oci_fetch_object($stmt)) != false) {
            $fieldTmp = new DbtGen_Model_Field($obj->COLUMN_NAME);
            $table->addField($fieldTmp);
        }
        oci_free_statement($stmt);
    }

    public function getDb()
    {
        return '';
    }

    public function getHost()
    {
        return '//' . $this->host . '/' . $this->db;
    }
}
