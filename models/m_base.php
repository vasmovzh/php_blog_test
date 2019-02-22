<?php
/**
 * base model
 */

class M_Base {
    protected $mysqli;    // database driver

    protected function __construct() {
        $this->mysqli = DB_Driver::getInstance();
    }
}