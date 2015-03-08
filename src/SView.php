<?php



class SView
{
    /** @var string $template */
    private static $templateFilename;
    /** @var array $outStack Binding vars sets into $template */
    private static $outStack = [];
    private static $pageError;

    /**
     * @param string $template Set layout file
     */
    public function __construct($template){
        self::$templateFilename = $template;
    }
    /**
     * Sets or get binding var
     * @param string        $name
     * @param null|array    $date
     * @return $this|null|string
     */
    public function addOutput($name, $date=null){
        if($date===null)
            return (isset(self::$outStack[$name])) ? self::$outStack[$name] : null;
        else
            self::$outStack[$name] = $date;
        return $this;
    }

    /**
     * Retrieves executed view file
     * @param string    $filename   view file
     * @param array     $data       view outStack
     * @return string
     */
    function partial($filename, $data=[]){
        ob_start(); extract($data);
        include($filename.'.php');
        return ob_get_clean();
    }

    /**
     * Render response executed layout
     */
    function render(){
        ob_start();
        extract(self::$outStack);
        include(self::$templateFilename.'.php');
        print(ob_get_clean());
    }

    /**
     * @param $name
     * @param bool $response
     * @return string
     */
    function output($name,$response=true){
        $data = empty(self::$outStack[$name]) ? '' : self::$outStack[$name];
        if($response)
            echo $data;
        else
            return $data;
    }

}


