<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PHPixie\Tests\Framework;

/**
 * Description of AssetsTest
 * @coversDefaultClass \PHPixie\Micro\Framework\Assets
 * @author sobolevna
 */
class AssetsTest extends \PHPixie\Test\Testcase{
    /**
     *
     * @var \PHPixie\Micro\Framework\Assets
     */
    protected $assets;
    
    /**
     *
     * @var \PHPixie\Micro\Framework\Builder 
     */
    protected $builder;
    
    
    public function setUp() {
        $this->builder = new \PHPixie\Micro\Framework\Builder();
        $this->assets = $this->builder->assets();
    }
    
    /**
     * @covers ::__construct
     */
    public function testConstructor() {
        $this->assertInstance($this->assets, '\PHPixie\Micro\Framework\Assets');        
    }
    
    /**
     * @covers ::root
     * @covers ::assetsRoot
     * @covers ::webRoot
     * @covers ::<protected>
     */
    public function testRoots() {
        $this->assertInstance($this->assets->root(), '\PHPixie\Filesystem\Root');
        $this->assertInstance($this->assets->assetsRoot(), '\PHPixie\Filesystem\Root');
        $this->assertInstance($this->assets->webRoot(), '\PHPixie\Filesystem\Root');
    }
    
    /**
     * @covers ::configStorage
     * @covers ::parameterStorage
     * @covers ::<protected>
     */
    public function testStorages() {
        $this->assertInstance($this->assets->configStorage(), '\PHPixie\Config\Storages\Type\Directory');
        $this->assertInstance($this->assets->parameterStorage(), '\PHPixie\Config\Storages\Type\File');
    }
}
