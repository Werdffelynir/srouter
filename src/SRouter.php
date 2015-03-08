<?php


class SRouterException extends RuntimeException { public $message; }

class SRouter
{
    private $basePath;
    private $baseUri;
    private $baseScriptName;
    private $requestUri;
    private $requestMethod;

    private $currentRequest;
    private $currentGetParams;

    private $routerResult;
    private $routerErrors;
    private $forceCallable = false;

    private $regReplaces = [
        ':n!' => '\d+',
        ':s!' => '[a-zA-Z]+',
        ':a!' => '\w+',
        ':p!' => '[\w\?\&\=\-\%\.\+]+',
        ':*!' => '[\w\?\&\=\-\%\.\+\/]+',
        ':n?' => '\d{0,}',
        ':s?' => '[a-zA-Z]{0,}',
        ':a?' => '\w{0,}',
        ':p?' => '[\w\?\&\=\-\%\.\+\{\}]{0,}',
        ':*?' => '[\w\?\&\=\-\%\.\+\{\}\/]{0,}',
        '/' => '\/',
        '<' => '?<',
        ').'=> ')\.',
    ];

    /**
     * Can accept params
     * <pre>
     * [
     *      'request_uri'=>'string',
     *      'request_method'=>'string',
     *      'base_uri'=>'string',
     *      'base_path'=>'string',
     *      'base_script_name'=>'string'
     * ];
     * </pre>
     * @param array $params
     */
    public function __construct(array $params=[])
    {
        $this->requestMethod = (empty($params['request_method'])) ?  strtoupper($_SERVER['REQUEST_METHOD']) : $params['request_method'];
        $this->basePath = (empty($params['base_path'])) ? '' : $params['base_path'];
        $this->baseUri = (empty($params['base_uri'])) ?  $_SERVER['HTTP_HOST'] : $params['base_uri'];
        $this->baseScriptName = (empty($params['base_script_name'])) ? pathinfo($_SERVER['SCRIPT_FILENAME'])['basename'] : $params['base_script_name'];

        // assign property Request Uri
        $requestUri = (empty($params['request_uri'])) ?  $_SERVER['REQUEST_URI'] : $params['request_uri'];
        $replaces = [$this->basePath,$this->baseScriptName];
        $this->requestUri = trim(str_ireplace($replaces,'',urldecode($requestUri)),'/');

        $this->determineRequestParams();
    }

    /**
     * Supported request methods
     *
     * @param string|array  $condition
     * @param callable      $callback
     * @param array         $callbackParams
     */
    public function post($condition,$callback,array $callbackParams=[]){
        $this->map('POST',$condition,$callback,$callbackParams);
    }
    public function get($condition,$callback,array $callbackParams=[]){
        $this->map('GET',$condition,$callback,$callbackParams);
    }
    public function put($condition,$callback,array $callbackParams=[]){
        $this->map('PUT',$condition,$callback,$callbackParams);
    }
    public function delete($condition,$callback,array $callbackParams=[]){
        $this->map('DELETE',$condition,$callback,$callbackParams);
    }
    public function options($condition,$callback,array $callbackParams=[]){
        $this->map('OPTIONS',$condition,$callback,$callbackParams);
    }
    public function xhr($condition,$callback,array $callbackParams=[]){
        $this->map('XHR',$condition,$callback,$callbackParams);
    }

    /**
     * @param string        $method
     * @param string|array  $condition
     * @param callable      $callback
     * @param array         $addedCallbackParams
     */
    public function map($method,$condition,$callback,array $addedCallbackParams=[])
    {
        if(is_array($condition)){
            foreach ($condition as $one) {
                $this->runProcessing($method,$one,$callback,$addedCallbackParams);
            }
        }else{
            $this->runProcessing($method,$condition,$callback,$addedCallbackParams);
        }
    }

    /**
     * @param string        $method
     * @param string|array  $condition
     * @param callable      $callback
     * @param array         $addedCallbackParams
     */
    private function runProcessing($method,$condition,$callback,$addedCallbackParams){

        // stop processing if you already have the result
        if(!empty($this->routerResult))
            goto mapEnd;


        // if request method is an indication
        if($this->requestMethod==$method || ($method == 'XHR' && $this->isXMLHTTPRequest()) ) {

            $callableParams = $this->conditionMatch($condition);
            if($callableParams) {

                $callbackParams = array_merge($addedCallbackParams,$callableParams['numberParams']);
                $this->routerResult = [
                    'method'    => $method,
                    'callable'  => $callback,
                    'params'    => $callbackParams,
                    'paramsGet' => $this->currentGetParams,
                ];
                if($this->forceCallable) {
                    if(is_callable($callback))
                        call_user_func_array($callback, (array) $callbackParams);
                    else
                        $this->routerErrors .= __LINE__."line. Error is no a callable $callback \n";
                }
            }
        }

        mapEnd:
    }

    /**
     * <pre>
     * Examples: $condition
     * user/(<name>:a?)
     * user/(<name>:a!)
     * user/(<id>:n!)
     * user/(<name>:a!)/(<id>:n!)'
     * page/(:p!)/(:p!)/(:p?)
     * page/(:*!) all valid symbols and separator / to
     * page/(:*!)/(:*!)/(:*!) WRONG !!!
     * </pre>
     * @param $condition
     * @return array 'namedParams'=> 'numberParams'=>
     */
    private function conditionMatch($condition)
    {
        $hewLimiter = true;

        if(strpos($condition,':*') !== false)
            $hewLimiter = false;

        # first handle
        $parts = explode('/', trim($condition,'/'));
        $toMap = '';
        foreach ($parts as $part) {
            $position = strpos($part, ":");
            if(strpos($part, "<") !== false || $position !== false){
                $part = (substr($part, $position+2, 1) == '?') ? "?($part)" : "($part)";
                //var_dump($position);
            }
            $toMap .= '/'.$part;
        }

        # second handle
        $toMap = strtr($toMap, $this->regReplaces);

        # third handle, params joins or if match success return empty params
        if(preg_match("|^{$toMap}$|i", $this->currentRequest, $result)){

            $namedParams = [];
            $numberParams = [];

            if(count($result)>1){

                array_shift($result);

                if($hewLimiter) {
                    foreach ($result as $resultKey=>$resultVal) {
                        if(is_string($resultKey))
                            $namedParams[$resultKey] = $resultVal;
                        else
                            $numberParams[] = $resultVal;
                    }
                }else{
                    $numberParams = explode('/',$result[0]);
                }
            }

            return [
                'namedParams' => $namedParams,
                'numberParams'=> $numberParams
            ];
        }
        return false;
    }


    /**
     *
     */
    private function determineRequestParams()
    {
        if(empty($this->requestUri))
            $case = '/';
        else
            $case = $this->requestUri;
        $params = null;

        if($this->requestMethod=='GET'){
            if(!empty($_GET)){
                $get = explode('?',$case);
                if(count($get)>1) {
                    $case = $get[0];
                    parse_str($get[1],$params);
                } else
                    $case = (string)$get;

            }else{
                $params = null;
            }
        }else
            parse_str(file_get_contents('php://input'), $params);

        $this->currentRequest = '/'.trim($case,'/');
        $this->currentGetParams = $params;
    }


    /**
     * @return mixed
     */
    public function getRouterResult() {
        return $this->routerResult;
    }


    public function getParams($name=null) {
        $_params  = $this->routerResult['params'];
        $_paramsGet = $this->routerResult['paramsGet'];
        $params = array_merge($_paramsGet,$_params);

        if($name !== null){
            if(isset($params[$name]))
                return $params[$name];
            else
                return null;
        }else{
            return $params;
        }
    }


    /**
     * @return mixed
     */
    public function getRouterErrors() {
        return $this->routerErrors;
    }


    /**
     * @param bool $force
     */
    public function forceCallable($force=true) {
        $this->forceCallable = (bool)$force;
    }


    /*to base*/
    public function link($link, $encodeSeparators = false) {
        if($encodeSeparators)
            return urlencode($link);
        $link = urlencode(str_replace(['&','='],['SEPARATOR_AND','SEPARATOR_TO'],$link));
        return str_replace(['SEPARATOR_AND','SEPARATOR_TO'],['&','='],$link);
    }

    public function baseUri($url=null){
        if($url===null)
            return $this->baseUri;
        $this->baseUri = $url;
    }

    public function isXMLHTTPRequest() {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) ==
            'xmlhttprequest') || isset($_GET['ajax']);
    }
}





