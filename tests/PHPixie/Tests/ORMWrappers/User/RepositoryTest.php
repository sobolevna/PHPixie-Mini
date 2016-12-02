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
    
    protected $entity;

    public function setUp() {
        $builder = new \PHPixie\Micro\Framework\Builder();
        $rep = $this->quickMock('PHPixie\ORM\Models\Type\Database\Repository');
        $query = $this->quickMock('PHPixie\ORM\Models\Type\Database\Query');
        $this->entity = $this->quickMock('\PHPixie\ORM\Models\Type\Database\Implementation\Entity');
        $this->method($query, 'findOne', $this->entity);
        $this->method($query, 'orWhere', null);
        $this->method($rep, 'query', $query);
        $this->stack = $builder->configuration()->ormWrappers()->userRepository($rep);
    }
    
    /**
     * @covers ::getByLogin
     * @covers ::<protected>
     */
    public function testLoginFields() {
        $this->assertEquals($this->stack->getByLogin('name'), $this->entity);
    }
}
