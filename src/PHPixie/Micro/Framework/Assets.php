<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PHPixie\Micro\Framework;

use PHPixie\Filesystem\Locators\Locator;
use PHPixie\Filesystem\Root;

/**
 * Description of Assets
 *
 * @author sobolevna
 */
class Assets extends \PHPixie\Framework\Assets {

    /**
     * @var string
     */
    protected $rootDirectory;

    /**
     * Constructor
     * @param Components $components
     * @param string $rootDirectory
     */
    public function __construct($components, $rootDirectory)
    {
        $this->rootDirectory = $rootDirectory;

        parent::__construct($components);
    }

    /**
     * Project folder
     * @return Root
     */
    public function root()
    {
        return $this->instance('root');
    }

    /**
     * Assets folder
     * @return Root
     */
    public function assetsRoot()
    {
        return $this->instance('assetsRoot');
    }

    /**
     * Web folder
     * @return Root
     */
    public function webRoot()
    {
        return $this->instance('webRoot');
    }

    /**
     * Configuration storage
     * @return \PHPixie\Config\Storages\Type\Directory
     */
    public function configStorage()
    {
        return $this->instance('configStorage');
    }

    /**
     * Parameter storage
     * @return \PHPixie\Config\Storages\Type\File
     */
    public function parameterStorage()
    {
        return $this->instance('parameterStorage');
    }

    /**
     * @return Root
     */
    protected function buildRoot()
    {
        return $this->buildFilesystemRoot(
            $this->rootDirectory
        );
    }

    /**
     * @return Root
     */
    protected function buildAssetsRoot()
    {
        return $this->buildFilesystemRoot(
            $this->root()->path('assets')
        );
    }

    /**
     * @return Root
     */
    protected function buildWebRoot()
    {
        return $this->buildFilesystemRoot(
            $this->root()->path('web')
        );
    }

    /**
     * @return \PHPixie\Config\Storages\Type\Directory
     */
    protected function buildConfigStorage()
    {
        $config = $this->components->config();

        return $config->directory(
            $this->assetsRoot()->path(),
            'config',
            'php',
            $this->parameterStorage()
        );
    }

    /**
     * @return \PHPixie\Config\Storages\Type\File
     */
    protected function buildParameterStorage()
    {
        $file = $this->assetsRoot()->path('parameters.php');

        $config = $this->components->config();
        return $config->file($file);
    }

}
