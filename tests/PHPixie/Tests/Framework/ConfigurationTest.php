<?php

namespace PHPixie\Tests\Framework;

/**
 * Description of ConfigurationTest
 * @coversDefaultClass \PHPixie\Micro\Framework\Configuration
 * @author sobolevna
 */
class ConfigurationTest extends \PHPixie\Test\Testcase {

    protected $builder;
    protected $configuration;
    protected $assets;
    protected $components;
    protected $configStorage;
    protected $parameterStorage;

    public function setUp() {
        $this->builder = $this->builder();
        $this->configuration = $this->configurationMock();
        $this->assets = $this->assets();
        $this->method($this->builder, 'assets', $this->assets, array());
        $this->configStorage = $this->quickMock('\PHPixie\Config\Storages\Storage');
        $this->method($this->assets, 'configStorage', $this->configStorage, array());
        $this->parameterStorage = $this->getSliceData();
        $this->method($this->assets, 'parameterStorage', $this->parameterStorage, array());
        $this->components = $this->components();
        $this->method($this->builder, 'components', $this->components, array());
    }

    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct() {
        
    }
    
    /**
     * @covers ::databaseConfig
     * @covers ::<protected>
     */
    public function testDatabaseConfig()
    {
        $this->configSliceTest('database');
    }
    
        /**
     * @covers ::socialConfig
     * @covers ::<protected>
     */
    public function testSocialConfig()
    {
        $this->configSliceTest('social');
    }
    
        /**
     * @covers ::httpConfig
     * @covers ::<protected>
     */
    public function testHttpConfig()
    {
        $this->configSliceTest('http');
    }
    
    /**
     * @covers ::templateConfig
     * @covers ::<protected>
     */
    public function testTemplateConfig()
    {
        $this->configSliceTest('template');
    }
    
    /**
     * @covers ::filesystemRoot
     * @covers ::<protected>
     */
    public function testFilesystemRoot()
    {
        $root = $this->quickMock('\PHPixie\Fielsystem\Root');
        $this->method($this->assets, 'root', $root, array(), 0);
        $this->assertSame($root, $this->configuration->filesystemRoot());
    }
    
        /**
     * @covers ::ormConfig
     * @covers ::<protected>
     */
    public function testOrmConfig()
    {
        $this->configuration = $this->configurationMock(array('orm'));
        $orm = $this->prepareConfigurationOrm();
        $config = $this->getSliceData();
        $this->method($orm, 'configData', $config, array(), 0);
        $this->assertSame($config, $this->configuration->ormConfig());
    }
    
    /**
     * @covers ::ormWrappers
     * @covers ::<protected>
     */
    public function testOrmWrappers()
    {
        $this->configuration = $this->configurationMock(array('orm'));
        $orm = $this->prepareConfigurationOrm();
        $wrappers = $this->quickMock('\PHPixie\ORM\Wrappers');
        $this->method($orm, 'wrappers', $wrappers, array(), 0);
        $this->assertSame($wrappers, $this->configuration->ormWrappers());
    }
    
        /**
     * @covers ::authConfig
     * @covers ::<protected>
     */
    public function testAuthConfig()
    {
        $this->configSliceTest('auth');
    }
    /**
     * @covers ::authRepositories
     * @covers ::<protected>
     */
    public function testAuthRepositories()
    {
        $bundlesAuth = $this->prepareBundlesAuth();
        $this->assertSame($bundlesAuth, $this->configuration->authRepositories());
    }
    /**
     * @covers ::httpProcessor
     * @covers ::<protected>
     */
    public function testHttpProcessor()
    {
        $httpProcessors = $this->prepareComponent('httpProcessors');
        $bundles        = $this->prepareComponent('bundles');
        $registry = $this->quickMock('\PHPixie\Processors\Registry');
        $this->method($bundles, 'httpProcessors', $registry, array());
        $processor = $this->quickMock('\PHPixie\Processors\Processor');
        $this->method($httpProcessors, 'attributeRegistryDispatcher', $processor, array(
            $registry,
            'bundle'
        ));
        for($i=0; $i<2; $i++) {
            $this->assertSame($processor, $this->configuration->httpProcessor());
        }
    }
    /**
     * @covers ::httpRouteResolver
     * @covers ::<protected>
     */
    public function testRouteResolver()
    {
        $route   = $this->prepareComponent('route');
        $bundles = $this->prepareComponent('bundles');
        $configData = $this->getSliceData();
        $this->method($this->configStorage, 'arraySlice', $configData, array('http.resolver'), 0);
        $registry = $this->quickMock('\PHPixie\Route\Resolvers\Registry');
        $this->method($bundles, 'routeResolvers', $registry, array());
        $resolver = $this->quickMock('\PHPixie\Route\Resolvers\Resolver');
        $this->method($route, 'buildResolver', $resolver, array(
            $configData,
            $registry
        ));
        for($i=0; $i<2; $i++) {
            $this->assertSame($resolver, $this->configuration->httpRouteResolver());
        }
    }

    protected function builder() {
        return $this->quickMock('\PHPixie\Micro\Framework\Builder');
    }

    protected function configurationMock($methods = null) {
        return $this->getMock(
                '\PHPixie\Micro\Framework\Configuration', $methods, array($this->builder)
        );
    }

    protected function assets() {
        return $this->quickMock('\PHPixie\Micro\Framework\Assets');
    }

    protected function getSliceData() {
        return $this->quickMock('\PHPixie\Slice\Data');
    }

    protected function components() {
        return $this->quickMock('\PHPixie\Micro\Framework\Components');
    }

    protected function prepareComponent($name) {
        if ($name === 'httpProcessors') {
            $class = '\PHPixie\Micro\HTTPProcessors';
        } else {
            $class = '\PHPixie\\' . ucfirst($name);
        }
        $mock = $this->quickMock($class);
        $this->method($this->components, $name, $mock, array());
        return $mock;
    }

    protected function configSliceTest($name, $key = null) {
        $parameterStorage = $this->getSliceData();
        $this->method($this->assets, 'parameterStorage', $parameterStorage, array());
        $this->method($parameterStorage, 'get', null, array('configOverlay'));
        if ($key === null) {
            $key = $name;
        }
        $slice = $this->getSliceData();
        $this->method($this->configStorage, 'arraySlice', $slice, array($key), 0);
        $method = $name . 'Config';
        for ($i = 0; $i < 2; $i++) {
            $this->assertSame($slice, $this->configuration->$method());
        }
    }

}
