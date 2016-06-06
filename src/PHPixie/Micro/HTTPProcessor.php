<?php

namespace PHPixie\Micro;

class HTTPProcessor extends \PHPixie\HTTPProcessors\Processor\Dispatcher\Builder\Attribute {

    protected $builder;
    protected $attribute = 'processor';

    public function __construct($builder) {
        $this->builder = $builder;
    }

    protected function buildActProcessor() {

        return new HTTPProcessors\Act(
                $this->builder
        );
    }

}
