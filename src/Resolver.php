<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Project;

/**
 * Description of Resolver
 *
 * @author sobolevna
 */
class Resolver implements \PHPixie\Route\Resolvers\Resolver {
    public function match($segment) {
            return new \PHPixie\Route\Translator\Match();
     }

     public function generate($match, $withHost = true) {
           throw new \Exception('not implemented');
     }
}
