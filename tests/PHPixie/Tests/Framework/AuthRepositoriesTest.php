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
        $builder = new \Project\Framework\Builder();
        $this->stack = new \Project\Framework\AuthRepositories($builder);
    }
    
    /**
     * @covers ::repository
     * @covers <protected>
     */
    public function testUserRepository() {
        $this->assertInstance($this->stack->repository('user'), '\Project\ORMWrappers\User\Repository');
    }
}
