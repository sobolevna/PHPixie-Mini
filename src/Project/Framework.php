<?php

namespace Project;

class Framework extends \PHPixie\Framework {

    protected $routeCount = array();

    /**
     * 
     * @return \Project\Framework\Builder
     */
    protected function buildBuilder() {
        return new Framework\Builder();
    }

    public function route($pattern, $func) {
        $pattern = $pattern[0] == '/' ? substr($pattern, 1) : $pattern;
        $cnt = count($this->routeCount);
        $this->routeCount[] = 'r' . $cnt;
        $id = $this->routeCount[$cnt];
        $config = $this->builder->configuration()->httpConfig()->slice('resolver.resolvers');
        $config->set(
            $id, 
            array(
                'type' => 'pattern',
                'path' => $pattern, 
                'defaults' => array(
                    'processor' => 'act', 
                    'action' => $id
                )
            )
        );
        echo '<pre>';
        //print_r($this->builder->configuration()->httpConfig()->getData());
        echo '</pre>';
        $proc = $this->builder->configuration()->httpProcessor()->processor('act');
        $proc->{$id . 'Action'} = $func;
    }

}
