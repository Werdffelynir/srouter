# Simple Router

SRouter - это простой класс для роутинга HTTP запросов, написан на PHP 5.4.
SRouter - это базис для REST API.
SRouter - поддержывает любые виды запросов GET, POST и созданые.


## Router catches HTTP methods:

### `GET`
```
$router->get('/page', callable);
```

### `POST`
```
$router->post('/page', callable);
```

### `PUT`
```
$router->put('/page', callable);
```

### `DELETE`
```
$router->delete('/page', callable);
```

### `OPTION`
```
$router->options('/page', callable);
```

### `XHR`
```
$router->xhr('/page', callable);
```

### `mixed `
```
$router->map('GET|POST', '/page', callable);
```


## Match rules
- `!` required
- `?` not required
- `:n?` numeric - `\d{0,}`,
- `:s?` words - `[a-zA-Z]{0,}`,
- `:a?` words - `\w{0,}`,
- `:p?` params - `[\w\?\&\=\-\%\.\+\{\}]{0,}`,
- `:*?` some symbols - `[\w\?\&\=\-\%\.\+\{\}\/]{0,}`,
- `:n!` required numeric - `\d+`,
- `:s!` required words - `[a-zA-Z]+`,
- `:a!` required words - `\w+`,
- `:p!` required params - `[\w\?\&\=\-\%\.\+]+`,
- `:*!` required some symbols - `[\w\?\&\=\-\%\.\+\/]+`,



## Class public methods

### `SRouter::__construct( [options] )` 


### `post($condition, $callback[, array $callbackParams])`


### `get($condition, $callback[, array $callbackParams])`


### `put($condition, $callback[, array $callbackParams])`


### `delete($condition, $callback[, array $callbackParams])`


### `options($condition, $callback[, array $callbackParams])`


### `xhr($condition, $callback[, array $callbackParams])`


### `map($method, $condition, $callback[, array $callbackParams])`


### `getRouterResult()`


### `getParams(['param_name'])`
все передаваемые данные, доступные посредством этого метода

### `getRouterErrors()`


### `getDomain()`


### `getUrl([$link])`


### `getFullUrl([$link])`


### `link([$link])`


### `isXMLHTTPRequest()`


### `forceRun()`


### `run()`




## Examples url rules
```

// site root 
// http://site.loc
$router->get('/', function(){
    echo "Home page";
});


// http://site.loc/contact
$router->get('/contact', function(){
    echo "Contact page";
});


// http://site.loc/hello/Vasia
$router->get('/hello/<user>:a!', function($user){
    echo "Hello $user";
});


// http://site.loc/user/12345
$router->get('/user/<id>:n!', function($id){
    echo "User ID $id";
});


// http://site.loc/doc
// http://site.loc/doc/some
$router->get('/doc/<link>:p?', function($link){
    echo "Doc link $link";
});


// http://site.loc/category/product
// http://site.loc/category/product/some
$router->get('/category/<category>:w?/<subcategory>:p?', function($category, $subcategory){
    echo "Category: $category, subcategory: $subcategory.";
});


// http://site.loc/doc/category/product
// http://site.loc/doc/category/product/some
$router->get('/doc/<category>:a!/<subcategory>:p?', function($category, $subcategory){
    echo "Document category: $category, subcategory: $subcategory.";
});


if($errors = $router->getRouterErrors())
    print_r($errors);
    
```


## Configuration
```
include('src/SRouter.php');

// additional options, I recommend using it if you see errors

$params = [
    'domain'=>'string',
    'base_path'=>'string',
    'request_uri'=>'string',
    'request_method'=>'string',
    'base_script_name'=>'string'
];


$router = new SRouter([$params]);


// its will start the execution of the immediately after a match router
$router->forceRun(true);


// show error if there are
if($errors = $router->getRouterErrors())
    print_r($errors);

```