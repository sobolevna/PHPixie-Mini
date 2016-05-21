<?php

namespace Project\Framework;

class Builder extends \PHPixie\Framework\Builder {
    
    public $root;
  
    public function configuration() {
        return $this->instance('configuration');
    }

    protected function buildConfiguration() {
        return new Configuration($this);
    }

    protected function getRootDirectory() {
        return realpath($root);
    }

}
