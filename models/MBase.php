<?php
/**********************************************************************
 * @author    Valeriy A. Smovzh aka vasmovzh (r) <vasmovzh@yandex.ru> *
 * @license   GNU AGPLv3                                              *
 * @copyright Copyright (c) 2019, vasmovzh (r)                        *
 * Date:      02.06.2019                                              *
 * Time:      21:21                                                   *
 * Project:   php_blog_test                                           *
 **********************************************************************/

namespace Models;

use mysqli;

/**
 * Base model class
 */
class MBase
{
    /**
     * Database driver
     *
     * @var mysqli
     */
    protected $mysqli;

    /**
     * MBase constructor
     */
    protected function __construct()
    {
        $this->mysqli = DbDriver::getInstance();
    }
}