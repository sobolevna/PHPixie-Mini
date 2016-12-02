<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PHPixie\Tests;

/**
 * Description of ORMWrappers
 * @coversDefaultClass \PHPixie\Micro\ORMWrappers
 * @author sobolevna
 */
class ORMWrappersTest extends \PHPixie\Test\Testcase {

    /**
     *
     * @var \PHPixie\Micro\ORMWrappers 
     */
    public $wrappers;

    public function setUp() {
        $this->wrappers = new \PHPixie\Micro\ORMWrappers();
    }

    /**
     * 
     * @param string $name
     * @param \Closure $func
     * @covers ::makeRepository
     * @covers ::__set
     * @covers ::__call
     * @covers ::<protected>
     * @dataProvider providerRepository
     */
    public function testMakeRepository($name, $func) {
        if (is_string($name) && is_callable($func)) {
            $this->wrappers->makeRepository($name, $func);
            $this->assertTrue(in_array($name, $this->wrappers->databaseRepositories()));
            $mock = $this->quickMock('PHPixie\ORM\Models\Type\Database');
            $this->assertInstance($this->wrappers->{$name . 'Repository'}($mock), '\PHPixie\ORM\Wrappers\Type\Database\Repository');
        }
    }

    public function providerRepository() {
        return array(
            [null, null],
            ['test', function($p) {
                    return new \PHPixie\ORM\Wrappers\Type\Database\Repository($p);
                }]
        );
    }

    /**
     * 
     * @param string $name
     * @param \Closure $func
     * @covers ::makeEntity 
     * @covers ::__set
     * @covers ::__call
     * @covers ::<protected>
     * @dataProvider providerEntity
     */
    public function testMakeEntity($name, $func) {
        if (is_string($name) && is_callable($func)) {
            $this->wrappers->makeEntity($name, $func);
            $this->assertTrue(in_array($name, $this->wrappers->databaseEntities()));
            $mock = $this->quickMock('PHPixie\ORM\Models\Type\Database');
            $this->assertInstance($this->wrappers->{$name . 'Entity'}($mock), '\PHPixie\ORM\Wrappers\Type\Database\Entity');
        }
    }

    public function providerEntity() {
        return array(
            [null, null],
            ['test', function($p) {
                    return new \PHPixie\ORM\Wrappers\Type\Database\Entity($p);
                }]
        );
    }

    /**
     * 
     * @param string $name
     * @param \Closure $func
     * @covers ::makeQuery
     * @covers ::__set
     * @covers ::__call
     * @covers ::<protected>
     * @dataProvider providerQuery
     */
    public function testMakeQuery($name, $func) {
        if (is_string($name) && is_callable($func)) {
            $this->wrappers->makeQuery($name, $func);
            $this->assertTrue(in_array($name, $this->wrappers->databaseQueries()));
            $mock = $this->quickMock('PHPixie\ORM\Models\Type\Database');
            $this->assertInstance($this->wrappers->{$name . 'Query'}($mock), '\PHPixie\ORM\Wrappers\Type\Database\Query');
        }
    }

    public function providerQuery() {
        return array(
            [null, null],
            ['test', function($p) {
                    return new \PHPixie\ORM\Wrappers\Type\Database\Query($p);
                }]
        );
    }

    /**
     * 
     * @param string $name
     * @param \Closure $func
     * @covers ::makeEmbeddedEntity
     * @covers ::__set
     * @covers ::__call
     * @covers ::<protected>
     * @dataProvider providerEmbeddedEntity
     */
    public function testMakeEmbeddedEntity($name, $func) {
        if (is_string($name) && is_callable($func)) {
            $this->wrappers->makeEmbeddedEntity($name, $func);
            $this->assertTrue(in_array($name, $this->wrappers->embeddedEntities()));
            $mock = $this->quickMock('PHPixie\ORM\Models\Type\Embedded');
            $this->assertInstance($this->wrappers->{$name . 'Entity'}($mock), '\PHPixie\ORM\Wrappers\Type\Embedded\Entity');
        }
    }

    public function providerEmbeddedEntity() {
        return array(
            [null, null],
            ['test', function($p) {
                    return new \PHPixie\ORM\Wrappers\Type\Embedded\Entity($p);
                }]
        );
    }

    /**
     * @covers ::userEntity
     * @covers ::userRepository
     * @covers ::__call
     */
    public function testUser() {
        $mock = $this->quickMock('PHPixie\ORM\Models\Type\Database');
        $this->assertInstance(
            $this->wrappers->userEntity($mock), 
            'PHPixie\Micro\ORMWrappers\User\Entity'
        );
        $this->assertInstance(
            $this->wrappers->userRepository($mock), 
            'PHPixie\Micro\ORMWrappers\User\Repository'
        );
    }

    /**
     * @covers ::__set
     * @covers ::__call
     */
    public function testSetCall() {
        if (!property_exists($this, 'prop1')) {
            $this->wrappers->prop1 = 1;
            $this->assertEquals($this->wrappers->prop1, 1);
        }
        if (!property_exists($this, 'prop2') && !method_exists($this, 'prop2')) {
            $callback =  function(){return true;};
            $this->wrappers->prop2 =$callback;
            $this->assertEquals($this->wrappers->prop2(), $callback());
        }
        if (method_exists($this, 'userEntity')) {
            $mock = $this->quickMock('PHPixie\ORM\Models\Type\Database');
            $this->assertInstance(
                $this->wrappers->userEntity($mock), 
                'PHPixie\Micro\ORMWrappers\User\Entity'
            );
        }
    }

}
