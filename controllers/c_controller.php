<?php
/**
 * abstract base class for controller
 */

abstract class C_Controller {
    // external template generation
    protected abstract function render();

    // function (method) that works before the main method
    protected abstract function before();

    /**
     * @param $action   - name of function
     */
    public function request($action) {
        $this->before();
        $this->$action();
        $this->render();
    }

    // clear from function (method) name
    protected function isGet() {
        return $_SERVER['REQUEST_METHOD'] == 'GET';
    }

    // clear from function (method) name
    // don't used in this case, made as pair for isGet() method
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    /**
     * template engine function (method)
     * @param $filename     - template file
     * @param array $params - associative array of pairs
     * @return false|string - generation of HTML into string
     */
    protected function template($filename, $params = array()) {
        if (file_exists($filename)) {
            extract($params);
            ob_start();
            include "$filename";
            return ob_get_clean();
        }
        else return "Template file \"$filename\" not found!";
    }

    // clear from function (method) name
    protected function redirect($url) {
        header("Location: $url");
        exit;
    }

    public function __call($name, $arguments) {
        header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        die("404 Page Not Found!");
    }
}