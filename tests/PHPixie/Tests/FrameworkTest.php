<?php

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
     * @dataProvider providerRoute
     */
    public function testRoute($pattern, $func) {
        $callback = function () use ($pattern, $func) {
            $this->framework->route($pattern, $func);
        };
        if (!is_callable($func)) {
            $this->assertException($callback, '\PHPixie\HTTPProcessors\Exception');
        } elseif (!(is_string($pattern) || is_numeric($pattern))) {
            $this->assertException($callback, '\PHPixie\Route\Exception\Route');
        } else {
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
            $this->assertEquals(
                $this->returnValue($proc->{$configId . 'Action'}), $this->returnValue($processorMock->{$configId . 'Action'})
            );
        }
    }

    public function providerRoute() {
        return array(
            [null, null],
            ['somepath', null],
            [null, function($param) {
                    return true;
                }],
            ['somepath', function($param) {
                    return true;
                }]
        );
    }

    /**
     * @covers ::wrapORM
     * @covers ::<protected>
     * @dataProvider providerWrapORM
     */
    public function testWrapORM($type, $name, $func) {
        $callback = function () use ($type, $name, $func) {
            $this->framework->wrapORM($type, $name, $func);
        };
        if (!(in_array(
                $type, array('repository', 'entity', 'embeddedEntity', 'query')
            ) && is_callable($func))
        ) {
            $this->assertException($callback, '\PHPixie\ORM\Exception\Builder');
        }
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
     * @dataProvider providerDB
     */
    public function testConfigureDB($name, array $config) {
        $callback = function () use ($name, $config) {
            $this->framework->confugureDB($name, $config);
        };
        if (!array_key_exists('driver', $config) || ($config['driver'] == 'mongo' && !array_key_exists('database', $config)) || ($config['driver'] != 'mongo' && !array_key_exists('connection', $config))
        ) {
            $this->assertException($callback, '\PHPixie\Database\Exception\Builder');
        } else {
            $callback();
            $ret = $this->framework->builder()->configuration()->databaseConfig()->getData($name);
            $this->assertEquals($ret, $config);
        }
    }

    public function providerDB() {
        return [
            ['a1', []],
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

    /**
     * @covers ::ormModel
     * @covers ::<protected>
     * @dataProvider providerOrmModel
     */
    public function testOrmModel($name, $config) {
        $callback = function () use($name, $config) {
            $this->framework->ormModel($name, $config);
        };
        if (!is_array($config)) {
            $this->assertException($callback, '\PHPUnit_Framework_Error');
        } elseif (!is_scalar($name)) {
            $this->assertException($callback, '\PHPixie\ORM\Exception\Model');
        } else {
            $keys = ['type', 'connection', 'idField'];
            foreach ($keys as $key) {
                if (!array_key_exists($key, $config)) {
                    $this->assertException($callback, '\PHPixie\ORM\Exception\Model');
                    return;
                }
            }
            $callback();
            $ret = $this->framework->builder()->configuration()->ormConfig()->getData('models.' . $name);
            $this->assertEquals($ret, $config);
        }
    }

    public function providerOrmModel() {
        return array(
            [null, null],
            ['name', 'config'],
            [[], []],
            ['name', []],
            ['name', [
                    'type' => 'database',
                    'connection' => 'mysql',
                    'idField' => 'id']
            ]
        );
    }

    /**
     * @covers ::ormRelationship
     * @covers ::<protected>
     * @dataProvider providerOrmRelationship
     */
    public function testOrmRelationship($config) {
        $callback = function () use($config) {
            $this->framework->ormRelationship($config);
        };
        if (!is_array($config)) {
            $this->assertException($callback, '\PHPUnit_Framework_Error');
        } else {
            try {
                $callback();
            } catch (\Exception $exc) {
                if (!($exc instanceof \PHPixie\ORM\Exception\Relationship)) {
                    $this->fail('testOrmRelationship: invalid Exception: ' . $exc->getLine());
                }
                return;
            }
            $ret = $this->framework->builder()->configuration()->ormConfig()->getData('relationships');
            $this->assertTrue(in_array($config, $ret));
        }
    }

    public function providerOrmRelationship() {
        return array(
            [[
                'type' => 'oneToMany',
                'owner' => 'project',
                'items' => 'task',
                //When a project is deleted
                //also delete all its tasks
                'itemsOptions' => array(
                    'onOwnerDelete' => 'delete'
                )]],
            [null],
            [[[]]],
            [[
                'type' => 1
                ]],
            [[
                'type' => 'oneToMany',
                'owner' => 'project'
                ]],
            [[
                'type' => 'oneToOne',
                'items' => 'task'
                ]],
            [[
                'type' => 'manyToMany',
                'right' => 'project',
                'left' => 'task'
                ]],
            [[
                'type' => 'manyToMany',
                'left' => 'task'
                ]],
            [[
                'type' => 'manyToMany',
                'right' => 'project'
                ]],
            [[
                'type' => 'embedsOne',
                'owner' => 'project',
                'items' => 'task'
                ]],
            [[
                'type' => 'embedsOne',
                'owner' => 'project',
                'item' => 'task'
                ]],
            [[
                'type' => 'nestedSet',
                'owner' => 'project',
                'items' => 'task'
                ]],
            [[
                'type' => 'nestedSet',
                'model' => 1
                ]]
        );
    }

    /**
     * @covers ::setAuthProviders
     * @covers ::<protected>
     * @dataProvider providerAuthProviders
     */
    public function testAuthProviders($config) {
        $callback = function () use($config) {
            $this->framework->setAuthProviders($config);
        };
        if (!is_array($config)) {
            $this->assertException($callback, '\PHPUnit_Framework_Error');
        } elseif (!$config || $config == []) {
            $this->assertException($callback, '\PHPixie\Auth\Exception');
        } else {
            $callback();
            $ret = $this->framework->builder()->configuration()->authConfig()->getData('domains.default.providers');
            $this->assertEquals($config, $ret);
        }
    }

    public function providerAuthProviders() {
        return array(
            [null],
            [1],
            [[]],
            [[1]]
        );
    }

}
