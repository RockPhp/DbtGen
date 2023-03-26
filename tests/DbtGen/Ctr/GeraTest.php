<?php
use PHPUnit\Framework\TestCase;

final class DbtGen_Ctr_GeraTest extends TestCase
{

    private function getTemplatePath()
    {
        return dirname(__DIR__, 3) . '/src/template';
    }

    private function getOutputPath()
    {
        return getcwd() . '/out';
    }

    /**
     *
     * @test
     */
    public function paths()
    {
        $dbtgenGera = new DbtGen_Ctr_Gera();
        $this->assertEquals($this->getTemplatePath(), $dbtgenGera->getDirTemplate());
        $this->assertEquals($this->getOutputPath(), $dbtgenGera->getDirOut());
    }
}

