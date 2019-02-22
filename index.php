<?php
/**
 * index
 */

require_once "settings.php";

error_reporting(E_ALL);
ini_set('display_errors', 'On');

setlocale(LC_ALL, 'ru_RU.UTF-8');
mb_internal_encoding('UTF-8');
header("Content-type:text/html;charset=utf-8");

session_start();

function __autoload($classname) {
    $classname = strtolower($classname);
    $dir_first_letter = mb_substr($classname, 0, 1);

    if ($dir_first_letter === "c") $dir_name = "controllers";
    else $dir_name = "models";

    if (is_file("{$dir_name}/{$classname}.php"))
        require_once "{$dir_name}/{$classname}.php";
    else die("File \"{$dir_name}/{$classname}.php\" not found!");
}

$action = "actionIndex";
if (isset($_GET['act']))
    $action = "action" . $_GET['act'];

$controller = new C_Articles();
$controller->request($action);