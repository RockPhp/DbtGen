<?php
use PHPUnit\Framework\TestCase;

final class DbtGen_FrontTest extends TestCase
{

    private function getViewPath()
    {
        return dirname(__DIR__, 2) . '/src/view/';
    }

    /**
     *
     * @test
     */
    public function front()
    {
        new DbtGen_Front();
    }

    /**
     *
     * @test
     */
    public function viewPath()
    {
        $dbtgenIndex = new DbtGen_Ctr_Index();
        $path = $dbtgenIndex->getViewPath();
        $this->assertEquals($this->getViewPath(), $path);
    }
}

