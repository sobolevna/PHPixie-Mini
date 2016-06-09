<?php

namespace PHPixie\Tests\Framework;

/**
 * Description of ConfigurationTest
 * @coversDefaultClass \PHPixie\Micro\Framework\Configuration
 * @author sobolevna
 */
class ConfigurationTest extends \PHPixie\Test\Testcase {

    /**
     *
     * @var \PHPixie\Micro\Framework\Builder 
     */
    protected $builder;

    /**
     *
     * @var \PHPixie\Micro\Framework\Configuration 
     */
    protected $configuration;

    /**
     *
     * @var \PHPixie\Micro\Framework\Assets 
     */
    protected $assets;

    /**
     *
     * @var \PHPixie\Micro\Framework\Components 
     */
    protected $components;

    /**
     *
     * @var \PHPixie\Config\Storages\Type\Directory 
     */
    protected $configStorage;

    /**
     *
     * @var \PHPixie\Config\Storages\Type\Directory 
     */
    protected $parameterStorage;

    public function setUp() {
        $this->builder = new \PHPixie\Micro\Framework\Builder();
        $this->configuration = $this->builder->configuration();
        $this->assets = $this->builder->assets();
        $this->configStorage = $this->builder->assets()->configStorage();
        $this->parameterStorage = $this->builder->assets()->parameterStorage();
        $this->components = $this->builder->components();
    }

    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct() {
        $this->assertInstance($this->configuration, '\PHPixie\Micro\Framework\Configuration');
    }

    /**
     * @covers ::databaseConfig
     * @covers ::ormConfig
     * @covers ::authconfig
     * @covers ::httpConfig
     * @covers ::templateConfig
     * @covers ::socialConfig
     * @covers ::httpRouteResolver
     * @covers ::<protected>
     */
    public function testConfigs() {
        $array = [
            'databaseConfig', 'ormConfig', 'authconfig', 'httpConfig', 'templateConfig', 'socialConfig', 'httpRouteResolver'
        ];
        foreach ($array as $method) {
            $this->assertInstance($this->configuration->{$method}(), '\PHPixie\Slice\Type\Slice\Editable');
        }
    }

    /**
     * @covers ::configStorage
     * @covers ::<protected>
     */
    public function testConfigStorage() {
        $this->assertEquals(
            $this->configuration->configStorage(), $this->configStorage
        );
    }

    /**
     * @covers ::filesystemRoot
     * @covers ::<protected>
     */
    public function testFilesystemRoot() {
        $this->assertEquals(
            $this->configuration->filesystemRoot(), $this->assets->root()
        );
    }

    /**
     * @covers ::httpProcessor
     * @covers ::<protected>
     */
    public function testHttpProcessor() {
        $this->assertInstance(
            $this->configuration->httpProcessor(), '\PHPixie\Micro\HTTPProcessor'
        );
    }

    /**
     * @covers ::ormWrappers
     * @covers ::<protected>
     */
    public function testOrmWrappers() {
        $this->assertInstance(
            $this->configuration->ormWrappers(), '\PHPixie\Micro\ORMWrappers'
        );
    }

    /**
     * @covers ::authRepositories
     * @covers ::<protected>
     */
    public function testAuthRepositories() {
        $this->assertInstance(
            $this->configuration->authRepositories(), 
            '\PHPixie\Micro\Framework\AuthRepositories'
        );
    }

    /**
     * @covers ::templateLocator
     * @covers ::<protected>
     */
    public function testTemplateLocator() {
        $userTpl = realpath(dirname(filter_input(INPUT_SERVER, 'SCRIPT_FILENAME')));
        if (is_dir($userTpl . '/template')) {
            $this->assertInstance(
                $this->configuration->templateLocator(), 
                '\PHPixie\Filesystem\Locators\Locator\Mount'
            );
        } else {
            $this->assertInstance(
                $this->configuration->templateLocator(), 
                '\PHPixie\Filesystem\Locators\Locator\Directory'
            );
        }
    }
}