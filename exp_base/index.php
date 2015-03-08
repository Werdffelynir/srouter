<?php

# .
require_once('../src/SRouter.php');


# .
$R = new SRouter();

# .
$R->forceCallable(true);


# .
echo '  <a href="/">home</a>
        <a href="/contact">contact</a>
        <a href="/hello/user">hello user</a>
        <a href="/doc/html">doc html</a>
        <a href="/doc/php/array_multisort">doc php 798</a>
        <a href="/item/4597548">item 4597548</a>
';


# .
$R->get('/', function(){
    echo "<h1>Home page</h1>";
});

$R->get('/contact', function(){
    echo "<h1>Contact page</h1>";
});

$R->get('/hello/<user>:a!', function($user){
    echo "<h1>Hello $user</h1>";
});

$R->get('/doc/<category>:a!/<subcategory>:p?', function($category, $subcategory){
    echo "<h1>Document category: $category, subcategory: $subcategory.</h1>";
});

$R->get('/item/:n!', function($id){
    echo "<h1>Item ID: $id.</h1>";
});


# .
if($errors = $R->getRouterErrors())
    print_r($errors);
