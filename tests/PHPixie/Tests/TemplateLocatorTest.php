<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PHPixie\Tests;

/**
 * Description of TemplateLocatorTest
 * @coversDefaltClass \PHPixie\Micro\TemplateLocator
 * @author sobolevna
 */
class TemplateLocatorTest extends \PHPixie\Test\Testcase{
    
    /**
     *
     * @var \PHPixie\Micro\TemplateLocator 
     */
    protected $stack;
    
    public function setUp() {
        $mock = $this->quickMock('PHPixie\Filesystem\Locators\Locator\Directory');
        $settings = ['loc1'=> $mock];
        $this->stack = new \PHPixie\Micro\TemplateLocator($settings);
    }
    
    /**
     * @covers ::__construct
     * @covers ::get
     */
    public function testLocator() {
        $mock = $this->quickMock('PHPixie\Filesystem\Locators\Locator\Directory');
        $this->assertEquals($this->stack->get('loc1'), $mock);
    }
}
