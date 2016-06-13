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
        $root1 = realpath(__DIR__ . '/../../../../');
        $root2 = realpath(__DIR__ . '/../../../../../../../');
        if (file_exists($root1. '/vendor')) {
            $path = $root1;
        } elseif (file_exists ($root2.'/vendor')) {
            $path = $root2;
        } else {
            throw new \Exception('Invalid filesystem root');
        }
        return $root1;
    }
}