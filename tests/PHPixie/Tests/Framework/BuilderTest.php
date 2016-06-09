<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PHPixie\Tests\Framework;

/**
 * Description of BuilderTest
 * @coversDefaultClass \PHPixie\Micro\Framework\Builder
 * @author sobolevna
 */
class BuilderTest extends \PHPixie\Test\Testcase {
    
    /**
     *
     * @var \PHPixie\Micro\Framework\Builder 
     */
    protected $builder;

    public function setUp() {
        $this->builder = new \PHPixie\Micro\Framework\Builder();
    }
    
    /**
     * @covers ::__construct
     */
    public function testConstructor() {
        
    }
    
    /**
     * @covers ::configuation
     * @covers <protected>
     */
    public function testConfiguration() {
        $this->assertInstance($this->builder->configuration(), 
            '\PHPixie\Micro\Framework\Configuration'
        );
    }
    
    /**
     * @covers ::assets
     * @covers <protected>
     */
    public function testAssets() {
        $this->assertInstance($this->builder->assets(), 
            '\PHPixie\Micro\Framework\Assets'
        );
    }
    
    
}
