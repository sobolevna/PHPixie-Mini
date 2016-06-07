<?php

namespace PHPixie\Micro\Framework;

class Builder extends \PHPixie\Framework\Builder {

    public function configuration() {
        return $this->instance('configuration');
    }

    protected function buildConfiguration() {
        return new Configuration($this);
    }

}
