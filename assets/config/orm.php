<?php

return array(
    'models' => [
        'project' => [
            'type' => 'database',
            'connection' => 'mysql',
            'idField' => 'id'
        ]
    ],
    'relationships' => array(
        ['type' => 'oneToMany',
        'owner' => 'project',
        'items' => 'task',
        //When a project is deleted
        //also delete all its tasks
        'itemsOptions' => array(
            'onOwnerDelete' => 'delete'
        )]
    )
);
