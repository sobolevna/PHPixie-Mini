<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Project\ORMWrappers;

/**
 * Description of Project
 *
 * @author sobolevna
 */
class Project extends \PHPixie\ORM\Wrappers\Type\Database\Entity{
    public function isDone()
    {
        return $this->tasksDone === $this->tasksTotal;
    }
}
