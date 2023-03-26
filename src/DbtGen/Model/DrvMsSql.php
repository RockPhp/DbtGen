<?php

class DbtGen_Model_DrvMsSql extends DbtGen_Model_Structure
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
        while ($obj = mssql_fetch_object($rs)) {
            $tableName = $obj->TABLE_SCHEMA . '.' . $obj->TABLE_NAME;
            $pkFields = $this->getPkFields($tableName);
            $tblTmp = new DbtGen_Model_Table($this->dbproj, $tableName, $pkFields);
            $this->setTableFields($tblTmp);
            $tables[] = $tblTmp;
        }
        return $tables;
    }

    public function __construct()
    {
        $this->driver = 'mssql';
    }

    private function connect()
    {
        if (self::$conn === null) {
            self::$conn = mssql_connect($this->host, $this->user, $this->passwd);
            mssql_select_db($this->db, self::$conn);
        }
    }

    /**
     *
     * @param string $table
     * @return string[]
     */
    private function getPkFields($table)
    {
        $tableSchema = explode('.', $table);
        $schema = $tableSchema[0];
        $table = $tableSchema[1];
        $this->connect();
        $query = "SELECT 
				Col.COLUMN_NAME,Tab.CONSTRAINT_TYPE from 
			    INFORMATION_SCHEMA.TABLE_CONSTRAINTS Tab, 
			    INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE Col 
				WHERE
			    Col.Constraint_Name = Tab.Constraint_Name
			    AND Col.Table_Name = Tab.Table_Name
			    AND Constraint_Type = 'PRIMARY KEY '
			    AND Col.CONSTRAINT_SCHEMA = '$schema'
			    AND Col.Table_Name = '$table'";
        $rs = mssql_query($query, self::$conn);
        $pkFields = array();
        while ($obj = mssql_fetch_object($rs)) {
            $pkFields[] = $obj->COLUMN_NAME;
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
        $query = 'SELECT TABLE_SCHEMA,TABLE_NAME 
				FROM information_schema.tables ORDER BY TABLE_SCHEMA,TABLE_NAME';
        return mssql_query($query, self::$conn);
    }

    /**
     *
     * @param DbtGen_Model_Table $table
     */
    private function setTableFields(DbtGen_Model_Table $table)
    {
        $tableSchema = explode('.', $table->getTableName());
        $schema = $tableSchema[0];
        $tableName = $tableSchema[1];
        $this->connect();
        $query = "SELECT 
				COLUMN_NAME from INFORMATION_SCHEMA.COLUMNS
				WHERE
				TABLE_SCHEMA = '$schema' AND
				TABLE_NAME='$tableName'";
        $rs = mssql_query($query, self::$conn);
        while ($obj = mssql_fetch_object($rs)) {
            $fieldTmp = new DbtGen_Model_Field($obj->COLUMN_NAME);
            $table->addField($fieldTmp);
        }
    }
}
