<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PHPixie\Mini;

/**
 * Description of Framework
 * A class to initialize microframeworks based on PHPixie.
 * An architecture is similar to other PHP microframeworks.
 * 
 * @author sobolevna
 */
class Framework extends \PHPixie\Framework {

    /**
     *
     * @var string Site root  
     */
    protected $dir;
    
    /**
     * @var array 
     */
    protected $routes = array();
    
    /**
     *
     * @var \PHPixie\Slice
     */
    protected $slice;

    public function __construct($dir) {
        $this->dir = isset($dir) && \is_dir($dir) ? $dir : $_SERVER['DOCUMENT_ROOT'];
        $this->slice = new \PHPixie\Slice();
        parent::__construct();
    }

    /**
     * @return Builder
     */
    protected function buildBuilder() {
        return new Builder();
    }

    
}
