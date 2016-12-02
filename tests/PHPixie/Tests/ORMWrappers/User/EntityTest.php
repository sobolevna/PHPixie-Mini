<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace PHPixie\Tests\ORMWrappers\User;
/**
 * Description of EntityTest
 * @coversDefaultClass \PHPixie\Micro\ORMWrappers\User\Entity
 * @author sobolevna
 */
class EntityTest extends \PHPixie\Test\Testcase {
    
    /**
     *
     * @var \PHPixie\Micro\ORMWrappers\User\Entity
     */
    protected $stack;

    public function setUp() {
        $builder = new \PHPixie\Micro\Framework\Builder();
        $entity = $this->quickMock('PHPixie\ORM\Models\Type\Database\Entity');
        $this->stack = $builder->configuration()->ormWrappers()->userEntity($entity);
    }
    
    /**
     * @covers ::passwordHash
     */
    public function testPasswordHash() {
        $this->stack->passwordHash = 'ergf';
        $this->assertEquals($this->stack->passwordHash, $this->stack->passwordHash());
    }
}
