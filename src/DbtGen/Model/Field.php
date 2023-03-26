<?php

class DbtGen_Model_Field
{

    private $name;

    private $isPk = false;

    public function getName()
    {
        return $this->name;
    }

    public function __construct($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     *
     * @return bool
     */
    public function isPk()
    {
        return $this->isPk;
    }

    public function setIsPk()
    {
        $this->isPk = true;
        return $this;
    }
}
