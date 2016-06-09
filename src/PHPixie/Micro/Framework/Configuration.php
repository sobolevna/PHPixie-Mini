<?php

namespace PHPixie\Micro\Framework;

class Configuration implements \PHPixie\Framework\Configuration {

    /**
     *
     * @var Builder 
     */
    protected $builder;
    /**
     *
     * @var array
     */
    protected $instances = array();

    /**
     * 
     * @param Builder $builder
     */
    public function __construct($builder) {
        $this->builder = $builder;
    }

    /**
     * 
     * @return \PHPixie\Slice\Type\Slice\Editable
     */
    public function authConfig() {
        return $this->instance('authConfig');
    }

    /**
     * 
     * @return \PHPixie\Micro\Framework\AuthRepositories
     */
    public function authRepositories() {
        return $this->instance('authRepositories');
    }

    /**
     * 
     * @return \PHPixie\Slice\Type\Slice\Editable
     */
    public function imageDefaultDriver() {
        return $this->instance('imageDefaultDriver');
    }

    /**
     * 
     * @return \PHPixie\Slice\Type\Slice\Editable
     */
    public function databaseConfig() {
        return $this->instance('databaseConfig');
    }

    /**
     * 
     * @return \PHPixie\Slice\Type\Slice\Editable
     */
    public function httpConfig() {
        return $this->instance('httpConfig');
    }

    /**
     * 
     * @return \PHPixie\Slice\Type\Slice\Editable
     */
    public function templateConfig() {
        return $this->instance('templateConfig');
    }

    /**
     * 
     * @return \PHPixie\Slice\Type\Slice\Editable
     */
    public function ormConfig() {
        return $this->instance('ormConfig');
    }

    /**
     * 
     * @return \PHPixie\Micro\ORMWrappers
     */
    public function ormWrappers() {
        return $this->instance('ormWrappers');
    }

    /**
     * 
     * @return \PHPixie\Micro\HTTPProcessor
     */
    public function httpProcessor() {
        return $this->instance('httpProcessor');
    }

    /**
     * 
     * @return \PHPixie\Slice\Type\Slice\Editable
     */
    public function httpRouteResolver() {
        return $this->instance('httpRouteResolver');
    }

    /**
     * 
     * @return \PHPixie\Slice\Type\Slice\Editable
     */
    public function templateLocator() {
        return $this->instance('templateLocator');
    }

    /**
     * 
     * @return \PHPixie\Slice\Type\Slice\Editable
     */
    public function socialConfig() {
        return $this->instance('socialConfig');
    }
    
    /**
     * 
     * @return \PHPixie\Config\Storages\Type\Directory
     */
    public function configStorage() {
        return $this->builder->assets()->configStorage();
    }
    
    /**
     * 
     * @return \PHPixie\Filesystem\Root
     */
    public function filesystemRoot() {
        return $this->builder->assets()->root();
    }

    /**
     * 
     * @param string $name
     * @return mixed
     */
    protected function instance($name) {
        if (!array_key_exists($name, $this->instances)) {
            $method = 'build' . ucfirst($name);
            $this->instances[$name] = $this->$method();
        }

        return $this->instances[$name];
    }

    /**
     * 
     * @return \PHPixie\Slice\Type\Slice\Editable
     */
    protected function buildDatabaseConfig() {
        return $this->configStorage()->slice('database');
    }

    /**
     * 
     * @return \PHPixie\Slice\Type\Slice\Editable
     */
    protected function buildOrmConfig() {
        return $this->configStorage()->slice('orm');
    }

    /**
     * 
     * @return \PHPixie\Micro\ORMWrappers
     */
    protected function buildOrmWrappers() {
        return new \PHPixie\Micro\ORMWrappers();
    }

    /**
     * 
     * @return \PHPixie\Slice\Type\Slice\Editable
     */
    protected function buildHttpConfig() {
        return $this->configStorage()->slice('http');
    }

    /**
     * 
     * @return \PHPixie\Slice\Type\Slice\Editable
     */
    protected function buildTemplateConfig() {
        return $this->configStorage()->slice('template');
    }

    /**
     * 
     * @return \PHPixie\Micro\HTTPProcessor
     */
    protected function buildHttpProcessor() {
        return new \PHPixie\Micro\HTTPProcessor($this->builder);
    }

    /**
     * @return \PHPixie\Route\Resolvers\Resolver
     */
    protected function buildHttpRouteResolver() {
        $components = $this->builder->components();

        return $components->route()->buildResolver(
                $this->configStorage()->slice('http.resolver')
        );
    }

    /**
     * 
     * @return \PHPixie\Filesystem\Locators\Locator\Directory|\PHPixie\Filesystem\Locators\Locator\Mount
     */
    protected function buildTemplateLocator() {
        $components = $this->builder->components();
        $userTpl = realpath(dirname(filter_input(INPUT_SERVER, 'SCRIPT_FILENAME')));
        if (is_dir($userTpl . '/template')) {

            $config1 = $this->configStorage()->slice('template.locator');
            $root1 = $this->filesystemRoot();
            $locator1 = $components->filesystem()->buildLocator(
                $config1, $root1
            );

            $config2 = $components->slice()->arrayData(array(
                'type' => 'directory',
                'directory' => 'template'
            ));
            $root2 = $components->filesystem()->root($userTpl);
            $locator2 = $components->filesystem()->buildLocator(
                $config2, $root2
            );
            $settings = ['loc1' => $locator1, 'loc2' => $locator2];
            $locatorConfig = $components->slice()->arrayData(array(
                'type' => 'group',
                'locators' => [
                    [ 'type' => 'mount',
                        'name' => 'loc1'],
                    [ 'type' => 'mount',
                        'name' => 'loc2']
                ]
            ));
            return $components->filesystem()->buildlocator(
                    $locatorConfig, $root1, new \PHPixie\Micro\TemplateLocator($settings)
            );
        } else {
            $config = $this->configStorage()->slice('template.locator');
            return $components->filesystem()->buildLocator(
                    $config, $this->filesystemRoot()
            );
        }
    }

/**
     * 
     * @return \PHPixie\Slice\Type\Slice\Editable
     */
    protected function buildAuthConfig() {
        return $this->configStorage()->slice('auth');
    }

    /**
     * 
     * @return \PHPixie\Micro\Framework\AuthRepositories
     */
    protected function buildAuthRepositories() {
        return new \PHPixie\Micro\Framework\AuthRepositories($this->builder);
    }

    /**
     * 
     * @return \PHPixie\Slice\Type\Slice\Editable
     */
    protected function buildImageDefaultDriver() {
        return $this->configStorage()->slice('image.defaultDriver', 'gd');
    }

    /**
     * 
     * @return \PHPixie\Slice\Type\Slice\Editable
     */
    protected function buildSocialConfig() {
        return $this->configStorage()->slice('social');
    }

}
