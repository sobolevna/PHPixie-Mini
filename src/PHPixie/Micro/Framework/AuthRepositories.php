<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PHPixie\Micro\Framework;

/**
 * Description of AuthRepositories
 *
 * @author sobolevna
 */
class AuthRepositories extends \PHPixie\Auth\Repositories\Registry\Builder {
    
    /**
     *
     * @var Builder
     */
    protected $builder;

    public function __construct($builder)
    {
        $this->builder = $builder;
    }

    protected function buildUserRepository()
    {
        $orm = $this->builder->components()->orm();
        return $orm->repository('user');
    }
}
