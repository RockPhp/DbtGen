<?php

class Dbt_DBGROUP_DbConnect extends Rock_DbAl_Conn
{

    protected static $quoteFields = false;

    protected static $quoteTableNames = false;

    public function __construct()
    {
        $dbhost = 'HOSTNAME';
        $dbname = 'DBNAME';
        $dbuser = 'DBUSER';
        $dbpassword = 'DBPASSWD';

        parent::__construct( //
        "jdbc:DBDRIVER://$dbhost/$dbname", //
        $dbuser, //
        $dbpassword //
        );
    }

    public static function isQuotedFields()
    {
        return self::$quoteFields;
    }

    public static function isQuoteTableNames()
    {
        return self::$quoteTableNames;
    }
}
