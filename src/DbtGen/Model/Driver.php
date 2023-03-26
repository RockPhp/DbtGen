<?php

class DbtGen_Model_Driver
{

    /**
     *
     * @param string $driver
     * @return DbtGen_Model_Structure
     */
    public static function getDb($driver)
    {
        $class = 'DbtGen_Model_Drv' . $driver;
        if (class_exists($class)) {
            return new $class();
        }
        throw new Exception('Driver ' . $driver . ' nao encontrado');
    }
}
