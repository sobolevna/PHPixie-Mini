<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PHPixie\Tests;

/**
 * Description of FrameworkTest
 *
 * @author sobolevna
 */
class FrameworkTest extends \PHPixie\Test\Testcase {

    protected $framework;

    public function setUp() {
        $this->framework = new \Project\Framework();
    }

    /**
     * @covers ::route
     * @covers ::<protected>
     * @expectedException \PHPixie\HTTPProcessors\Exception
     * @expectedException \PHPixie\Route\Exception\Route
     * @dataProvider testRouteProvider
     */
    public function testRoute($pattern, $func) {
        $config = $this->framework->builder()->configuration()
                ->httpConfig()->slice('resolver.resolvers')->getData();
        $processorMock = $this->quickMock('\Project\HTTPPRocessors\Act');
        $proc = $this->framework->builder()->configuration()->httpProcessor()
            ->processor('act');
        $this->assertInstance($proc, '\Project\HTTPPRocessors\Act');
        $this->framework->route($pattern, $func);
        $configId = 'r'.(count($config)-1);
        $config[$configId] = [
            'type' => 'pattern',
            'path' => $pattern,
            'defaults' => array(
                'processor' => 'act',
                'action' => $configId
            )
        ];
        $configNew = $this->framework->builder()->configuration()
                ->httpConfig()->slice('resolver.resolvers')->getData();
        $this->assertTrue($configNew == $config);
        $processorMock->{$configId.'Action'} = \Closure::bind($func, $processorMock);
        $request = $this->quickMock('\PHPixie\HTTP\Request');
        $this->assertEquals(
            $this->returnValue($proc->{$configId.'Action'}),
            $this->returnValue($processorMock->{$configId.'Action'})
        );
    }
    
    public function testRouteProvider() {
        return array(
            [],
            ['somepath', null],
            [null, function($request){return true;}],
            [[], function($request){return true;}],
            ['somepath', function($request){return true;}]            
        );
        
    }

}
