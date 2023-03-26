<?php

class DbtGen_Model_Table
{

    private $dbName;

    private $tableName;

    /**
     *
     * @var array
     */
    private $pkFields = array();

    /**
     *
     * @var DbtGen_Model_Field[]
     */
    private $fields = array();

    /**
     * Retorna nome da tabela
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    public function __construct($dbName, $tableName, array $pkFields = array())
    {
        $this->dbName = $dbName;
        $this->pkFields = $pkFields;
        $this->tableName = $tableName;
        return $this;
    }

    /**
     * Retorna campos da tabela
     *
     * @return DbtGen_Model_Field[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    public function addField(DbtGen_Model_Field $field)
    {
        array_push($this->fields, $field);
        if (in_array($field->getName(), $this->pkFields)) {
            $field->setIsPk();
        }
        return $this;
    }

    public function getPkFields()
    {
        return $this->pkFields;
    }

    public function getDbName()
    {
        return $this->dbName;
    }
}
