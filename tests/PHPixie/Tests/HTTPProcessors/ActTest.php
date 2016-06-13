<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PHPixie\Tests\HTTPProcessors;

/**
 * Description of ActTest
 * @coversDefaultClass \PHPixie\Micro\HTTPProcessors\Act
 * @author sobolevna
 */
class ActTest extends \PHPixie\Test\Testcase {

    /**
     *
     * @var \PHPixie\Micro\HTTPProcessors\Act
     */
    protected $act;

    /**
     *
     * @var \PHPixie\Micro\Framework\Builder 
     */
    protected $builder;
    protected $rqMock;

    public function setUp() {
        $this->builder = new \PHPixie\Micro\Framework\Builder();
        $this->act = $this->builder->configuration()->httpProcessor()->processor('act');
    }

    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstructor() {
        $this->assertInstance($this->act, '\PHPixie\Micro\HTTPProcessors\Act');
    }

    /**
     * @covers ::__set
     * @covers ::__call
     * @covers ::isProcessable
     * @covers ::<protected>
     * @dataProvider providerAct
     */
    public function testIsProcessable($name, $value) {
        $prep = $this->prepare($name, $value);
        if ($prep) {
            $this->assertTrue($prep());
        }
    }

    /**
     * @covers ::__set
     * @covers ::__call
     * @covers ::process
     * @covers ::<protected>
     * @dataProvider providerAct
     */
    public function testProcess($name, $value) {
        $prep = $this->prepare($name, $value, 1);
        if ($prep) {
            $this->assertEquals(
                $this->returnValue($prep()), $this->returnValue($this->act->{$name . 'Action'}($this->rqMock))
            );
        }
    }

    public function providerAct() {
        return array(
            [null, null],
            ['', function($param) {
                    return $param;
                }],
            ['test', null],
            ['test', 'test'],
            ['test', function($param) {
                    return $param;
                }]
        );
    }

    /**
     * This method sanitizes input because it has already been sanitized in 
     * Framework::route()
     * @param string $name
     * @param Closure $value
     * @param mixed $param
     * @return mixed
     */
    protected function prepare($name, $value, $param = null) {
        if (!is_string($name) || !$name || !$value || !is_callable($value)) {
            return null;
        }
        $this->act->{$name . 'Action'} = $value;
        $proc = $this->act;
        $rqMock = $this->quickMock('\PHPixie\HTTP\Request');
        $g = $this->quickMock('\PHPixie\Slice\Type\ArrayData');
        $this->method($rqMock, 'attributes', $g);
        $this->method($g, 'get', $name);
        $this->rqMock = $rqMock;
        if ($param) {
            $callback = function () use($proc, $rqMock) {
                return $proc->process($rqMock);
            };
        } else {
            $callback = function () use($proc, $rqMock) {
                return $proc->isProcessable($rqMock);
            };
        }
        return $callback;
    }

}
