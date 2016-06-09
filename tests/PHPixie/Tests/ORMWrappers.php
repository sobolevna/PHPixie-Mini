<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PHPixie\Tests;

/**
 * Description of ORMWrappers
 *
 * @author sobolevna
 */
class ORMWrappers extends \PHPixie\Test\Testcase{
    
    /**
     *
     * @var \PHPixie\Micro\ORMWrappers 
     */
    public $wrappers;
    
    public function setUp() {
        $this->wrappers = new \PHPixie\Micro\ORMWrappers(); 
    }
    
    
}
