<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PHPixie\Micro\ORMWrappers\User;

/**
 * Description of Repository
 *
 * @author sobolevna
 */
class Repository extends \PHPixie\AuthORM\Repositories\Type\Login {

    // You can supply multiple login fields,
    // in this case its both usernam and email
    protected function loginFields() {
        return array('username', 'email');
    }

}
