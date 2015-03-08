# Simple router


This simple URL Router


## Match rules
- :n! require numeric. regexp: '\d+'
- :s! require words. regexp: '[a-zA-Z]+'
- :a! require words. regexp: '\w+'
- :p! require params. regexp: '[\w\?\&\=\-\%\.\+]+'
- :*! require some. regexp: '[\w\?\&\=\-\%\.\+\/]+'
- :n?
- :s?
- :a?
- :p?
- :*?

## Configuration
```
include('src/SRouter.php');


$R = new SRouter();


// its will start the execution of the immediately after a match router
$R->forceCallable(true);


$R->get('/', function(){
    echo "Main page";
});


$R->get('/contact', function(){
    echo "Contact page";
});


$R->get('/hello/<user>:a!', function($user){
    echo "Hello $user";
});


if($errors = $R->getRouterErrors())
    print_r($errors);


```