<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PHPixie\Tests\Framework;

/**
 * Description of AuthRepositoriesTest
 *
 * @author sobolevna
 */
class AuthRepositoriesTest extends \PHPixie\Test\Testcase {
    
    protected $stack;

    public function setUp() {
        $builder = new \PHPixie\Micro\Framework\Builder();
        $this->stack = new \PHPixie\Micro\Framework\AuthRepositories($builder);
    }
    
    /**
     * @covers ::repository
     * @covers <protected>
     */
    public function testUserRepository() {
        $this->assertInstance($this->stack->repository('user'), '\PHPixie\Micro\ORMWrappers\User\Repository');
    }
}
