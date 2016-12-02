<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PHPixie\Tests\Framework;

/**
 * Description of AuthRepositoriesTest
 * @coversDefaultClass \PHPixie\Micro\Framework\AuthRepositories
 * @author sobolevna
 */
class AuthRepositoriesTest extends \PHPixie\Test\Testcase {
    
    /**
     *
     * @var \PHPixie\Micro\Framework\AuthRepositories
     */
    protected $stack;

    public function setUp() {
        $builder = new \PHPixie\Micro\Framework\Builder();
        $this->stack = $builder->configuration()->authRepositories();
    }
    
    /**
     * @covers ::__construct
     */
    public function testConstruct() {
        $this->assertInstance($this->stack, '\PHPixie\Micro\Framework\AuthRepositories');
    }
    
    /**
     * @covers ::repository
     * @covers ::<protected>
     */
    public function testUserRepository() {
        $this->assertInstance($this->stack->repository('user'), '\PHPixie\Micro\ORMWrappers\User\Repository');
    }
}
