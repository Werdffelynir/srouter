# Simple Router

SRouter - это простой класс для роутинга HTTP запросов, написан на PHP 5.4.
SRouter - это базис для REST API.
SRouter - поддержывает любые виды запросов GET, POST и созданые.


## Router catches HTTP methods:

### `GET`
```
$R->get('/page', callable);
```

### `POST`
```
$R->post('/page', callable);
```

### `PUT`
```
$R->put('/page', callable);
```

### `DELETE`
```
$R->delete('/page', callable);
```

### `OPTION`
```
$R->options('/page', callable);
```

### `XHR`
```
$R->xhr('/page', callable);
```

### `mixed `
```
$R->map('GET|POST', '/page', callable);
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

// additional options, I recommend using it if you see errors

$params = [
    'domain'=>'string',
    'base_path'=>'string',
    'request_uri'=>'string',
    'request_method'=>'string',
    'base_script_name'=>'string'
];


$R = new SRouter([$params]);


// its will start the execution of the immediately after a match router
$R->forceRun(true);


// show error if there are
if($errors = $R->getRouterErrors())
    print_r($errors);

```