<?php

namespace PHPixie\Micro\Framework;

class Builder extends \PHPixie\Framework\Builder {

    /**
     * 
     * @return \PHPixie\Micro\Framework\Configuration
     */
    public function configuration() {
        return $this->instance('configuration');
    }
    
    /**
     * 
     * @return Assets
     */
    public function assets() {
        return parent::assets();
    }

    /**
     * 
     * @return \PHPixie\Micro\Framework\Configuration
     */
    protected function buildConfiguration() {
        return new Configuration($this);
    }

    /**
     * @return Assets
     */
    protected function buildAssets() {
        return new Assets(
            $this->components(), $this->getRootDirectory()
        );
    }
    
    /**
     * Specifies your PHPixie root 
     * @return string
     * @throws \Exception
     */
    protected function getRootDirectory() {
        if (file_exists(__DIR__ . '/../../../../vendor') && dirname(__DIR__ . '/../../../../../') != 'vendor'
        ) {
            $path = realpath(__DIR__ . '/../../../../');
        } elseif (dirname(__DIR__ . '/../../../../../') == 'vendor') {
            $path = realpath(__DIR__ . '/../../../../../../../');
        } else {
            throw new \Exception('Invalid filesystem root');
        }
        return $path;
    }
}