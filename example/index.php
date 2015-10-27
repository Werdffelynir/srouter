<?php

require_once('../src/SRouter.php');


$config = [];


// Для мультиязычности, может использоватся логика следующего кода.
// Отрезается часть uri и распознается как язык, остально передается для роутинга.
/*
$requestURI = trim($_SERVER['REQUEST_URI'],'/');
$langs = ['en','ru','ua'];
$lang = 'en';
if(strpos($requestURI,'/') == 2 && in_array(substr($requestURI,0,2), $langs)) {
    $lang = substr($requestURI,0,2);
    $requestURI = substr($requestURI,3);
}
$config['request_uri'] = $requestURI;
*/


// если индекс (файл обработчик) роутера находится не в корне приложения,
// нужно указать базовый путь к файлу обработчику запросов,
// значение вобщем практически всешжа совпадает с параметром конфигурации модуля apache Rewrite (RewriteBase /path/to/)
/*
$config['base_path'] = '/path/to/';
*/


define('URL', '/');


$router = new SRouter($config);


$router->get('/',function(){
    render([
        'title' => 'Home',
        'content' => 'Wellcome to sRouter',
    ]);
});


$router->get('/about',function(){
    render([
        'title' => 'About',
        'content' => 'About sRouter',
    ]);
});


$router->get('/contacts',function(){
    render([
        'title' => 'Contacts',
        'content' => 'Contacts whith as',
    ]);
});


$router->map('GET|POST','/items',function(){

    $request = (!empty($_POST)) ? $_POST : ((!empty($_GET)) ? $_GET : []);

    $request = (!empty($request)) ? '<div><pre>'.print_r($request,true).'</pre></div>' : '';
    render([
        'title' => 'Items',
        'content' => '
           <div>
                <form action="" method="post">
                    <input type="text" name="type" hidden="hidden" value="POST">
                    <input type="text" name="name">
                    <input type="text" name="pass">
                    <input type="submit" value="Sent POST">
                </form>
            </div>
            <div>
                <form action="" method="get">
                    <input type="text" name="type" hidden="hidden" value="GET">
                    <input type="text" name="name">
                    <input type="text" name="pass">
                    <input type="submit" value="Sent GET">
                </form>
            </div>
            '.$request.'',
    ]);
});


$router->notFount(function(){
    render([
        'title' => 'Error 404',
        'content' => 'Page not fount!',
    ]);
});



function render($data){
    extract($data);
    echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>'.$title.'</title>
    <link rel="stylesheet" href="/main.css">
    <style>

    </style>
</head>
<body>
<div id="page">
    <div id="menu">
        <ul>
            <li><a href="/">main </a></li>
            <li><a href="/about">about</a></li>
            <li><a href="/contacts">contacts</a></li>
            <li><a href="/notfount">notFount</a></li>
            <li><a href="/items">items</a></li>
        </ul>
    </div>
    <div id="content" class="tbl">
        <div id="left" class="tblCell">
            <p>Sidebar</p>
        </div>
        <div id="right" class="tblCell">
            <h1>'.$title.'</h1>
            <div>'.$content.'</div>
        </div>
    </div>
    <div id="footer">author @ author</div>
</div>
</body>
</html>
';
}

$router->run();