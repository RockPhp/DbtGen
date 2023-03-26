<?php

abstract class Dbt_DBGROUP_Dao_Gen_TABLENAMECLASS extends Rock_Dbt_DaoBase
{

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->quoteFields = false;
        $this->quoteTableNames = false;
    }

    /**
     *
     * @return Dbt_DBGROUP_Ent_TABLENAMECLASS[]
     */
    public function getAll(Rock_Dbt_EntityBase $entity, $start = null, $limit = null)
    {
        return parent::getAll($entity, $start, $limit);
    }

    /**
     *
     * @return Dbt_DBGROUP_Ent_TABLENAMECLASS
     */
    public function getByPk(Rock_Dbt_EntityBase $entity)
    {
        return parent::getByPk($entity);
    }

    /**
     *
     * @return Dbt_DBGROUP_Ent_TABLENAMECLASS[]
     */
    public function getFiltered(Rock_Dbt_EntityBase $entity, $start = NULL, $limit = NULL)
    {
        return parent::getFiltered($entity, $start, $limit);
    }
}
