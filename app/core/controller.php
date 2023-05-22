<?php
abstract class Controller {
    protected static $csrf;

    protected static function generateCsrfToken() { // za varnost
        if (isset($_SESSION['token'])) {
            unset($_SESSION['token']);
        }
        $key = bin2hex(random_bytes(32));
        $_SESSION['token'] = self::$csrf = hash_hmac('sha256', "a csrf token for: ".$_SERVER['PHP_SELF'].".php", $key);
    }

    protected static function getCsrfToken() { // ne bo dostop od 3ta oseba
        self::$csrf = $_SESSION['token'];
        unset($_SESSION['token']);
    }

    protected function view(string $viewName, array $viewData = []) {
        $view = new View($viewName, $viewData);
        $view->render();
    }

    protected function redirect($path) {
        header("location: $path");
    }

    protected function checkForSpecialChars(string $string, bool $whiteSpace = true) {
        if ($whiteSpace) {
            $specialChars = ['č', 'Č', 'š', 'Š', 'ž', 'Ž', 'ć', 'Ć', 'đ', 'Đ', ' ', '<', '>', '/', '\\', PATH_SEPARATOR, DIRECTORY_SEPARATOR, PHP_EOL];
        } else {
            $specialChars = ['č', 'Č', 'š', 'Š', 'ž', 'Ž', 'ć', 'Ć', 'đ', 'Đ', '<', '>', '/', '\\', PATH_SEPARATOR, DIRECTORY_SEPARATOR, PHP_EOL];
        }
        foreach($specialChars as $specialChar) {
            if(strpos($string, $specialChar) !== false)
                return true;
        }
        return false;
    }
}