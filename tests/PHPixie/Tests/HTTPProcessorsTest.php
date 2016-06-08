<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace PHPixie\Tests;

/**
 * Description of HTTPProcessors
 * @coversDefaultClass PHPixie\Micro\HTTPProcessors
 * @author sobolevna
 */
class HTTPProcessorsTest extends \PHPixie\Test\Testcase {
    
    /**
     *
     * @var \PHPixie\Micro\Framework\Builder 
     */
    protected $builder;
    
    /**
     *
     * @var \PHPixie\Micro\HTTPProcessor
     */
    protected $processor;

    public function setUp() {
        $this->builder = new \PHPixie\Micro\Framework\Builder();
        $this->processor = $this->builder->configuration()->httpProcessor();
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct() {
        $this->assertInstance($this->processor, '\PHPixie\Micro\HTTPProcessor');
    }
    
    /**
     * @covers ::buildActProcessor
     * @covers ::<protected>
     */
    public function testActBuilder() {
        $this->assertInstance(
            $this->processor->process('act'), 
            'PHPixie\Micro\HTTPProcessors\Act'            
        );
    }
}
