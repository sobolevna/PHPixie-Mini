<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Project;

/**
 * Description of TemplateLocator
 *
 * @author sobolevna
 */
class TemplateLocator  implements \PHPixie\Filesystem\Locators\Registry {
    
    protected $settings;

    public function __construct(array $settings) {
        $this->settings = $settings;
    }
    public function get($name) {
       return $this->settings[$name];
    }
}
