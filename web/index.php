<?php

require_once(__DIR__ . '/../vendor/autoload.php');

$framework = new Project\Framework();
$framework->route('', function($request) {
    if (!(is_string(1))) echo 'grfsz ';
    return 'root';
});
$framework->route('test(/<param>)', function($request) {
    echo 'redafv';
    return 'SUCCESS!!!<br>' . $request->attributes()->get('param');
});
$framework->route('test/<param1>/space', function($request) {
    echo 'redafv';
    return 'SUCCESS!!!<br>' . $request->attributes()->get('param1') . '/space';
});

$framework->route('test_orm', function($request) {
    $orm = $this->builder->components()->orm();
    $projects = $orm->query('project')->findOne();
    if (!$projects->isDone()) echo 'hted<br>';
    //Convert enttities to simple PHP objects
    return $projects;
});

$framework->wrapORM('entity', 'project', function($param) {
    $obj = new Project\ORMWrappers\Project($param);
//    var_dump($obj);
    return $obj;
});

$framework->route('test_tpl/<message>', function($request) {
    $tpl = $this->builder->components()->template();
    return $tpl->render(
            'tpl1', array(
                'message' => $request->attributes()->get('message')
            )
    );
});
$framework->registerDebugHandlers();
$framework->processHttpSapiRequest();
