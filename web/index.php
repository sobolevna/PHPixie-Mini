<?php
include_once(__DIR__.'/../vendor/autoload.php');
$framework = new \PHPixie\Micro\Framework();
$framework->route('', function(){return 'root';});
$framework->processHttpSapiRequest();
