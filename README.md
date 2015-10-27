# Simple Router

SRouter - это простой класс для роутинга HTTP запросов, написан на PHP 5.4.
SRouter - это базис для REST API.
SRouter - поддержывает любые виды запросов GET, POST и созданые.


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
Принимает параметры настройки роутера:
```php
[
    'domain'=>'site.com',
    'base_path'=>'/path/to/root/', 
    'request_uri'=>'',
    'request_method'=>'',
    'base_script_name'=>''
]
```


- 'domain' Настройки доменого имени если оно отличается от реального. Например: 'mysite.com'
- 'base_path' Базовый путь к роутеру, если индекс находится не в корене, нужно указать путь. например если сайт лежит в под-директории (www/mysite.com/some/index.php): /some/


#### `post($condition, $callback[, array $callbackParams])`
Отлавлевает HTTP запрос с методом __POST__. если `$condition` соответствуют, выполняет `$callback`


#### `get($condition, $callback[, array $callbackParams])`
Отлавлевает HTTP запрос с методом __GET__. если `$condition` соответствуют, выполняет `$callback`


#### `put($condition, $callback[, array $callbackParams])`
Отлавлевает HTTP запрос с пользовательским методом __PUT__. если `$condition` соответствуют, выполняет `$callback`


#### `delete($condition, $callback[, array $callbackParams])`
Отлавлевает HTTP запрос с пользовательским методом __DELETE__. если `$condition` соответствуют, выполняет `$callback`


#### `options($condition, $callback[, array $callbackParams])`
Отлавлевает HTTP запрос с пользовательским методом __OPTIONS__. если `$condition` соответствуют, выполняет `$callback`


#### `xhr($condition, $callback[, array $callbackParams])`
Отлавлевает HTTP запрос с пользовательским методом __XHR__. если `$condition` соответствуют, выполняет `$callback`


#### `map($method, $condition, $callback[, array $callbackParams])`
Создает пользователь наблюдатель за запросом, или смешаный наблюдатель
```php
$router->map('GET|POST', '/page', function(){
    // ... code
});
```

#### `getPort()`
Return current request port


#### `getProtocol()`
Return current request protocol, http or https


#### `getRouterResult()`
Returns an array, or null if no one rule does not fit


#### `getParams(['param_name'])`
все передаваемые данные, доступные посредством этого метода


#### `getRouterErrors()`
Return router errors for current request


#### `getDomain()`
Return current domain name


#### `getUrl([$link])`
Return and or create base relative url
And append $link


#### `getFullUrl([$link])`
Return and or create base absolute url
And append $link


#### `encodeLink([$link])`
Кодирукт строку как часть URL


#### `decodeLink([$link])`
Декодирукт строку с URL формата


#### `isXMLHTTPRequest()`
Проверка является ли запрос асинхронным. проверяет наличие заголовка 'HTTP_X_REQUESTED_WITH'


#### `forceRun()`
Executed immediately when finding matching, and skip the other rules


#### `run()`
Start implementation of the rules of the first found, after checking all the rules


#### `notFount($callback[,array $callbackParam])`
Executed $callback with $callbackParams, when no one rule does not fit


## Examples SRouter url rules
```php
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

## Examples SRouter Configuration
```php
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