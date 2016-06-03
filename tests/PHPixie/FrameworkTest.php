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
     * 
     */
    public function testRoute() {
        $pattern = 'somepath';
        $func = function($request) {
            return true;
        };
        $config = $this->framework->builder()->configuration()
                ->httpConfig()->slice('resolver.resolvers')->getData();
        $processorMock = $this->quickMock('\Project\HTTPPRocessors\Act');
        $proc = $this->framework->builder()->configuration()->httpProcessor()
            ->processor('act');
        $this->assertInstance($proc, '\Project\HTTPPRocessors\Act');
        $this->framework->route($pattern, $func);
        $configId = 'r' . (count($config));
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
        $this->assertEquals($configNew, $config);
        $processorMock->{$configId . 'Action'} = \Closure::bind($func, $processorMock);
        $request = $this->quickMock('\PHPixie\HTTP\Request');
        $this->assertEquals(
            $this->returnValue($proc->{$configId . 'Action'}), $this->returnValue($processorMock->{$configId . 'Action'})
        );
    }

    /**
     * @covers ::route
     * @covers ::<protected>
     * @expectedException \PHPixie\Route\Exception\Route
     */
    public function testRoutePattern() {
        $pattern = array(null, array(), 12, 'somepath');
        foreach ($pattern as $p) {
            $this->framework->route($p, function($request) {
                return true;
            });
        }
    }

    /**
     * @covers ::route
     * @covers ::<protected>
     * @expectedException \PHPixie\HTTPProcessors\Exception
     */
    public function testRouteFunc() {
        $func = array(null, array(), 12, 'somepath', function($request) {
            return true;
        });
        foreach ($func as $f) {
            $this->assertEquals($this->framework->route('somepath', $f), $this->framework);
        }
    }

    /**
     * @covers ::wrapORM
     * @covers ::<protected>
     * @expectedException \Exception
     * @dataProvider providerWrapORM
     */
    public function testWrapORM($type, $name, $func) {
        $this->framework->wrapORM($type, $name, $func);
        $wrappers = $this->framework->builder()->configuration()->ormWrappers();
        $this->assertTrue(is_callable($wrappers->{$name.ucfirst($type)}));
    }

    public function providerWrapORM() {
        return array(
            array('entity', '12', function() {
                    return true;
                }),
            ['query', 'rgfsrav', function($entity) {
                    return new \Project\ORMWrappers\Project($entity);
                }],
            ['repository', 'null', null],
            ['12', 'rgfsrav', null]
        );
    }

    /**
     * @covers ::confugureDB
     * @covers ::<protected>
     * @expectedException \PHPixie\Database\Exception\Builder
     * @dataProvider providerDB
     */
    public function testConfigureDB($name, array $config) {
        $this->framework->confugureDB($name, $config);
        $ret = $this->framework->builder()->configuration()->databaseConfig()->getData($name);
        if($ret == $config) echo 12345;
        $this->assertEquals($ret, $config);
    }

    public function providerDB() {
        return [
            ['a1', [null, null]],
            ['a2', [
                    'driver' => 'pdo',
                    'connection' => 'sqlite'
                ]],
            ['a3', [
                    'driver' => 'mongo',
                    'database' => 'htesfb'
                ]],
            ['a4', [
                    'driver' => 'mongo'
                ]],
        ];
    }

}
