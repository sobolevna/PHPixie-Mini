<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return array(
    'default' => array(
        'driver' => 'pdo',
        'connection' => 'sqlite::memory:'
    ),
    'mysql' => array(
        'driver' => 'pdo',
        'connection' => 'mysql:host=localhost;dbname=quickstart',
        'user'     => 'pixie',
        'password' => 'pixie'
    )
);