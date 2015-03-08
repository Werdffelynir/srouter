# Simple router


This simple URL Router


## Router catches URI methods
+ `GET` - `$R->get('/get', callable);`
+ `POST` - `$R->post('/post', callable);`
+ `PUT` - `$R->put('/put', callable);`
+ `DELETE` - `$R->delete('/delete', callable);`
+ `OPTION` - `$R->options('/options', callable);`
+ `XHR` - `$R->xhr('/ajax', callable);`
all transmitted data accessible to the method `getParams(['param_name'])`


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


## Examples url rules
```
// http://site.loc
$R->get('/', callable);

// http://site.loc/home
$R->get('/home', callable);

// http://site.loc/user/12345
$R->get('/user/<id>:n!', callable);

// http://site.loc/doc
// http://site.loc/doc/some
$R->get('/doc/<link>:p?', callable);

// http://site.loc/category/product
// http://site.loc/category/product/some
$R->get('/category/<cat>:w?/<subcat>:p?', callable);


```


## Configuration
```
include('src/SRouter.php');


// additional options, I recommend using it you see errors
$params = [
    'request_uri'=>'string',
    'request_method'=>'string',
    'base_uri'=>'string',
    'base_path'=>'string',
    'base_script_name'=>'string'
];


$R = new SRouter([$params]);


// its will start the execution of the immediately after a match router
$R->forceCallable(true);


// show error if there are
if($errors = $R->getRouterErrors())
    print_r($errors);


```