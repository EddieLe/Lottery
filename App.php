<?php

class App {

    public function __construct()
    {
        $url = $this->parseUrl();
        $controllerName = "$url[1]";

        require_once "api/$controllerName.php";
        $controller = new $controllerName;
        $methodName = $url[2];
        echo $methodName;
        unset($url[1]); unset($url[2]);
        $params = $url ? array_values($url) : Array();
        call_user_func_array(Array($controller, $methodName), $params);
    }

    public function parseUrl()
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            $url = rtrim($_SERVER['REQUEST_URI'], "/");
            $url = explode("/", $url);
            return $url;
        }
    }

}
