<?php

class DbtGen_Model_DrvPgSql extends DbtGen_Model_Structure
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
        while ($obj = pg_fetch_object($rs)) {
            $pkFields = $this->getPkFields($obj->table_name);
            $tblTmp = new DbtGen_Model_Table($this->dbproj, $obj->table_name, $pkFields);
            $this->setTableFields($tblTmp);
            $tables[] = $tblTmp;
        }
        return $tables;
    }

    public function __construct()
    {
        $this->driver = 'pgsql';
    }

    private function connect()
    {
        if (self::$conn === null) {
            $connectionString = "host=" . $this->host . " port=5432 dbname=";
            $connectionString .= $this->db . " user=";
            $connectionString .= $this->user . " password=" . $this->passwd;

            self::$conn = pg_pconnect($connectionString);
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
        $query = "SELECT          
          pg_attribute.attname
        FROM pg_index, pg_class, pg_attribute 
        WHERE 
          pg_class.oid = '\"$table\"'::regclass AND
          indrelid = pg_class.oid AND
          pg_attribute.attrelid = pg_class.oid AND 
          pg_attribute.attnum = any(pg_index.indkey)
          AND indisprimary";
        $rs = pg_query(self::$conn, $query);
        $pkFields = array();
        while ($obj = pg_fetch_object($rs)) {
            $pkFields[] = $obj->attname;
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
        $query = "SELECT
               table_name
            FROM
                information_schema.tables
            WHERE
                table_type = 'BASE TABLE'
            AND
                table_schema NOT IN ('pg_catalog', 'information_schema')
            AND
            	(table_name not ilike '%bkp%' and table_name not ilike '%backup%')
            order by table_name";
        return pg_query(self::$conn, $query);
    }

    /**
     *
     * @param DbtGen_Model_Table $table
     */
    private function setTableFields(DbtGen_Model_Table $table)
    {
        $this->connect();
        $query = "SELECT column_name FROM 
            information_schema.columns WHERE table_name ='" . $table->getTableName() . "'";
        $rs = pg_query(self::$conn, $query);
        while ($obj = pg_fetch_object($rs)) {
            $fieldTmp = new DbtGen_Model_Field($obj->column_name);
            $table->addField($fieldTmp);
        }
    }
}
