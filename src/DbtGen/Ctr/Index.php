<?php

class DbtGen_Ctr_Index implements Rock_Core_IController
{

    public function getViewPath()
    {
        return dirname(__DIR__, 3) . '/src/view/';
    }

    public function handle()
    {
        $vl = new Rock_Core_ViewLoader($this->getViewPath());
        $vl->load('Index');
    }
}
