<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PHPixie\Micro\ORMWrappers\User;

/**
 * Description of Entity
 *
 * @author sobolevna
 */
// Entity wrapper
class Entity extends \PHPixie\AuthORM\Repositories\Type\Login\User {

    // get hashed password value
    // from the field in the database
    public function passwordHash() {
        return $this->passwordHash;
    }

}
