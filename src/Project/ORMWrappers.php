<?php

namespace Project;

class ORMWrappers extends \PHPixie\ORM\Wrappers\Implementation {

    protected $functions = array();

    public function __set($name, $data) {
        if (is_callable($data)) {
            $this->functions[$name] = \Closure::bind($data, $this);
        } else
            $this->$name = $data;
    }
    
    public function __call($method, $args) {
        if (isset($this->functions[$method])) {

            return call_user_func_array($this->functions[$method], $args);
        }
        return call_user_func_array($this->$method, $args);
    }

    public function makeRepository($name, $func) {
        $this->databaseRepositories[] = $name;
        $this->{$name.'Repository'} = $func;
    }
    
    public function makeQuery($name, $func) {
        $this->databaseQueries[] = $name;
        $this->{$name.'Query'} = $func;
    }
    
    public function makeEntity($name, $func) {
        $this->databaseEntities[] = $name;

        $this->__set($name.'Entity', $func);
    }
    
    public function makeEmbeddedEntity($name, $func) {
        $this->embeddedEntities[] = $name;
        $this->{$name.'Entity'} = $func;
    }
}
