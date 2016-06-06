<?php

require_once(__DIR__.'/../vendor/autoload.php');

$framework = new Project\Framework();
$framework->route('', function($request){return 'root';});
$framework->route('test(/<param>)', function($request){echo 'redafv'; return 'SUCCESS!!!<br>'.$request->attributes()->get('param');});
$framework->route('test/<param1>/space', function($request){echo 'redafv'; return 'SUCCESS!!!<br>'.$request->attributes()->get('param1').'/space';});
$framework->route('test1/<param1>/<param2>', function($request){return $request->attributes()->get('param1').'/space/'.$request->attributes()->get('param2');});
$framework->registerDebugHandlers();
$framework->processHttpSapiRequest();