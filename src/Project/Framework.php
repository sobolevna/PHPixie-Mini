<?php
namespace Project;

class Framework extends \PHPixie\Framework
{
    protected $routeCount = array();


    protected function buildBuilder()
    {
        return new Framework\Builder();
    }
    
    public function route($pattern, $func) {
        $this->routeCount[]= 'r'.count($this->routeCount);
        $id = $this->routeCount[count($this->routeCount)-1];
        $config = $this->builder->configuration()->httpConfig()->slice('resolver.resolvers');
        $config->set(
            $id, array(
                'type'     => 'pattern',
                'path'     => $pattern,
                'processor' => 'act',
                'action'    => $id
            )
        );
        
    }
}