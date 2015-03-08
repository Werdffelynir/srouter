# Simple router


This simple URL Router


## Configuration
```
include('src/SRouter.php');


$R = new SRouter();


// its will start the execution of the immediately after a match router
$R->forceCallable(true);


$R->get('/', function(){
    echo "<h1>Main page</h1>";
});


$R->get('/contact', function(){
    echo "<h1>Contact page</h1>";
});


$R->get('/hello/<user>:a!', function($user){
    echo "<h1>Hello $user</h1>";
});


if($errors = $R->getRouterErrors())
    print_r($errors);


```