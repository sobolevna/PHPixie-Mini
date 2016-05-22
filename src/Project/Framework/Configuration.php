<?php

namespace Project\Framework;

class Configuration implements \PHPixie\Framework\Configuration {

    protected $builder;
    protected $instances = array();

    public function __construct($builder) {
        $this->builder = $builder;
    }

    public function authConfig() {
        return $this->instance('authConfig');
    }

    public function authRepositories() {
        $this->instance('authRepositories');
    }

    public function imageDefaultDriver() {
        return $this->configStorage()->get('image.defaultDriver', 'gd');
    }

    public function databaseConfig() {
        return $this->instance('databaseConfig');
    }

    public function httpConfig() {
        return $this->instance('httpConfig');
    }

    public function templateConfig() {
        return $this->instance('templateConfig');
    }

    public function filesystemRoot() {
        return $this->instance('filesystemRoot');
    }

    public function assetsRoot() {
        return $this->instance('assetsRoot');
    }

    public function ormConfig() {
        return $this->instance('ormConfig');
    }

    public function ormWrappers() {
        return $this->instance('ormWrappers');
    }

    public function httpProcessor() {
        return $this->instance('httpProcessor');
    }

    public function httpRouteResolver() {
        return $this->instance('httpRouteResolver');
    }

    public function templateLocator() {
        return $this->instance('templateLocator');
    }

    protected function instance($name) {
        if (!array_key_exists($name, $this->instances)) {
            $method = 'build' . ucfirst($name);
            $this->instances[$name] = $this->$method();
        }

        return $this->instances[$name];
    }

    protected function buildDatabaseConfig() {
        return $this->configStorage()->slice('database');
    }

    protected function buildOrmConfig() {
        return $this->configStorage()->slice('orm');
    }

    protected function buildOrmWrappers() {
        return new \Project\ORMWrappers();
    }

    protected function buildHttpConfig() {
        return $this->configStorage()->slice('http');
    }

    protected function buildTemplateConfig() {
        return $this->configStorage()->slice('template');
    }

    protected function buildFilesystemRoot() {
        $filesystem = $this->builder->components()->filesystem();

        $path = realpath(__DIR__ . '/../../../');
        return $filesystem->root($path);
    }

    protected function buildAssetsRoot() {
        $filesystem = $this->builder->components()->filesystem();

        $path = $this->filesystemRoot()->path('/assets');
        return $filesystem->root($path);
    }

    protected function buildHttpProcessor() {
        return new \Project\HTTPProcessor($this->builder);
    }

    protected function buildHttpRouteResolver() {
        $components = $this->builder->components();

        return $components->route()->buildResolver(
                        $this->configStorage()->slice('http.resolver')
        );
    }

    protected function buildTemplateLocator() {
        $components = $this->builder->components();
        $userTpl = realpath($_SERVER['DOCUMENT_ROOT']);
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
//            var_dump($config2);
            $locator2 = $components->filesystem()->buildLocator(
                    $config2, $root2
            );
//            var_dump($locator2);
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
                            $locatorConfig, $root1, new \Project\TemplateLocator($settings)
            );
        } else {
            $config = $this->configStorage()->slice('template.locator');
            return $components->filesystem()->buildLocator(
                            $config, $this->filesystemRoot()
            );
        }
    }

    protected function configStorage() {
        return $this->instance('configStorage');
    }

    protected function buildConfigStorage() {
        $config = $this->builder->components()->config();

        return $config->directory(
                        $this->assetsRoot()->path(), 'config'
        );
    }

    protected function buildAuthConfig() {
        return $this->configStorage()->slice('auth');
    }

    protected function buildAuthRepositories() {
        return new \Project\AuthRepositories();
    }
}
