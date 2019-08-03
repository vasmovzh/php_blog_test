<?php
/**********************************************************************
 * @author    Valeriy A. Smovzh aka vasmovzh (r) <vasmovzh@yandex.ru> *
 * @license   GNU AGPLv3                                              *
 * @copyright Copyright (c) 2019, vasmovzh (r)                        *
 * Date:      03.08.2019                                              *
 * Time:      10:15                                                   *
 * Project:   php_blog_test                                           *
 **********************************************************************/

namespace Controllers;

/**
 * Abstract controller class
 */
abstract class CController
{
    /**
     * External template generation method
     *
     * @return mixed|void
     */
    abstract protected function render();

    /**
     * Function (method) that works before the main method
     *
     * @return mixed|void
     */
    abstract protected function before();

    /**
     * Request method
     *
     * @param string $action name of action
     */
    public function request(string $action)
    {
        $this->before();
        $this->$action();
        $this->render();
    }

    /**
     * Checker is GET request method
     *
     * @return bool
     */
    protected function isGet(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * Checker is POST request method
     *
     * @return bool
     *
     * @deprecated don't used in this case, made as pair for isGet() method
     */
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Template engine function (method)
     *
     * @param string $filename template file
     * @param array  $params   associative array of pairs
     *
     * @return false|string generation of HTML into string
     */
    protected function template(string $filename, array $params = [])
    {
        if (file_exists($filename)) {
            extract($params);
            ob_start();
            /** @noinspection PhpIncludeInspection */
            include $filename;
            return ob_get_clean();
        } else {
            return sprintf('Template file "%s" not found!', $filename);
        }
    }

    /**
     * Method of redirection
     *
     * @param string $url
     */
    protected function redirect(string $url)
    {
        header('Location: ' . $url);
        exit;
    }

    public function __call($name, $arguments)
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        die('404 Page Not Found!');
    }
}
