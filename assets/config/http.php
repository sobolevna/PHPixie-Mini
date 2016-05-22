<?php

return array(
    'translator' => array(
        'basePath' => '/'
    ),
    'resolver' => array(
        'type' => 'group',
        'resolvers' => array(
            /*'default' => array(
                'type'     => 'pattern',
                'path'     => '(<action>)',
                'defaults' => array(
                    'processor' => 'act',
                    'action'    => 'default'
                )
            )*/
        )
    ),
    'exceptionResponse' => array(
        'template' => 'http/exception'
    ),
    'notFoundResponse' => array(
        'template' => 'http/notFound'
    )
);
