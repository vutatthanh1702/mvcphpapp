<?php
class setSys
    {   
    public $sysRoot;

    public function setSystemRoot($path)
    {   
        $this->sysRoot = rtrim($path, '/');
    }   
}
