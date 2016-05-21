<?php

/*
 * Copyright (C) 2016 sobolev
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

/**
 * Description of Act
 *
 * @author sobolev
 */

namespace Project\HTTPProcessors;

class Act extends \PHPixie\HTTPProcessors\Processor\Actions\Attribute {

    protected $template;
    private $functions = array();

    public function __construct($builder) {
        $this->builder = $builder;
    }

    public function __set($name, $data) {
        if (is_callable($data)) {
            $this->functions[$name] = $data;
        } else
            $this->$name = $data;
    }

    public function __call($method, $args) {
        if (isset($this->functions[$method])) {
            $args[] = &$this;
            return call_user_func_array($this->functions[$method], $args);
        }
        return call_user_func_array($this->$method, $args);
    }

    public function isProcessable($request) {
        $action = $request->attributes()->get('action');
        return isset($this->functions[$action.'Action']);
    }

    public function process($request) {
        $action = $request->attributes()->get('action');
        return $this->functions[$action.'Action']($request);
    }

    public function defaultAction($request) {
        $container = $this->template->get('greet');
        $container->message = "Have fun coding!";
        return $container;
    }

}
