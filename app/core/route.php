<?php
class Route {
    public static function get(string $url, string $controller, string $method) {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && self::checkPath($url)) {
            if (file_exists(CONTROLLER.$controller.'.php')) {
                $controller = new $controller();
                if(method_exists($controller, $method)) {
                    call_user_func_array([$controller, $method], self::getParams($url));
                    return true;
                } 
            }
        }
        return false;
    }

    public static function post(string $url, string $controller, string $method) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && self::checkPath($url)) {
            if (file_exists(CONTROLLER.$controller.'.php')) {
                $controller = new $controller();
                if(method_exists($controller, $method)) {
                    call_user_func_array([$controller, $method], self::getParams($url));
                    return true;
                }
            }
        }
        return false;
    }

    public static function put(string $path, string $controller, string $method) {
        if (isset($_POST['_method'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['_method'] === 'PUT' && self::checkPath($path)) {
                if (file_exists(CONTROLLER . $controller . '.php')) {
                    $controller = new $controller();
                    if (method_exists($controller, $method)) {
                        call_user_func_array([$controller, $method], self::getParams($path));
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public static function delete(string $path, string $controller, string $method) {
        echo $_SERVER['REQUEST_METHOD'];
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE' ) {
            if (file_exists(CONTROLLER . $controller . ".php")) {
                $controller = new $controller();
                if (method_exists($controller, $method)) {
                    call_user_func_array([$controller, $method], self::getParams($path));
                    return true;
                }
            }
        }
        return false;
    }

    private static function getParams($path) {
        $request = trim($_SERVER['REQUEST_URI'], '/');
        $path = trim($path, '/');
        if (($index = self::hasWildCard($path)) !== false) {
            $requestArr = explode('/', $request);
            if (array_key_exists($index, $requestArr)) {
                return [$requestArr[$index]];
            }     
        }
        return [];
    }

    private static function checkPath($path) {
        $request = trim($_SERVER['REQUEST_URI'], '/');
        $requestArr = explode('/', $request);
        $path = trim($path, '/');
        $pathArr = explode("/", $path);
        if (($index = self::hasWildCard($path)) !== false && array_key_exists($index, $requestArr)) {
            array_splice($requestArr, $index, 1);
            array_splice($pathArr, $index, 1);
            $request = implode('/',$requestArr);
            $path = implode('/',$pathArr);
             if ($path !== $request) {
                return false;
            }
            return true;
        }
        else if ($path === $request) {
            return true;
        }
        else {
            return false;
        }
    }

    private static function hasWildCard($path) {
        $pathArr = explode('/', $path);
        $wildCard = end($pathArr);
        if(strpos($wildCard, '{') !== false && strpos($wildCard, '}') !== false) {
            return array_key_last($pathArr);
        }
        return false;
    }

}