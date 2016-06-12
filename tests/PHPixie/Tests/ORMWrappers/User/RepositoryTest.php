<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PHPixie\Tests\ORMWrappers\User;

/**
 * Description of RepositoryTest
 * @coversDefaultClass \PHPixie\Micro\ORMWrappers\User\Repository
 * @author sobolevna
 */
class RepositoryTest extends \PHPixie\Test\Testcase {
    /**
     *
     * @var \PHPixie\Micro\ORMWrappers\User\Repository
     */
    protected $stack;

    public function setUp() {
        $builder = new \PHPixie\Micro\Framework\Builder();
        $rep = $this->quickMock('PHPixie\ORM\Models\Type\Database\Repository');
        $this->stack = $builder->configuration()->ormWrappers()->userRepository($rep);
    }
    
    /**
     * @covers ::__construct
     */
    public function testConstruct() {
        $this->assertInstance($this->stack, '\PHPixie\Micro\ORMWrappers\User\Repository');
    }
}
