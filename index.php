<?php
/**********************************************************************
 * @author    Valeriy A. Smovzh aka vasmovzh (r) <vasmovzh@yandex.ru> *
 * @license   GNU AGPLv3                                              *
 * @copyright Copyright (c) 2019, vasmovzh (r)                        *
 * Date:      02.06.2019                                              *
 * Time:      20:58                                                   *
 * Project:   php_blog_test                                           *
 **********************************************************************/

use Controllers\CArticles;

require_once "settings.php";

error_reporting(E_ALL);
ini_set('display_errors', 'On');

setlocale(LC_ALL, 'ru_RU.UTF-8');
mb_internal_encoding('UTF-8');
header('Content-type:text/html;charset=utf-8');

session_start();

/**
 * Autolader
 *
 * @param string $className class name
 */
function __autoload(string $className)
{
    $dirFirstLetter = mb_substr($className, 0, 1);
    $dirName        = $dirFirstLetter === 'C' ? 'Controllers' : 'Models';
    $fileName       = $dirName . '/' . $className . '.php';

    if (is_file($fileName)) {
        /** @noinspection PhpIncludeInspection */
        require_once $fileName;
    } else {
        die(sprintf('File "%s/%s.php" not found!', $dirName, $className));
    }
}

$action = "actionIndex";
if (isset($_GET['act'])) {
    $action = 'action' . $_GET['act'];
}

$controller = new CArticles();
$controller->request($action);
