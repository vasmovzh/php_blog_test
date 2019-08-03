<?php
/**********************************************************************
 * @author    Valeriy A. Smovzh aka vasmovzh (r) <vasmovzh@yandex.ru> *
 * @license   GNU AGPLv3                                              *
 * @copyright Copyright (c) 2019, vasmovzh (r)                        *
 * Date:      19.05.2019                                              *
 * Time:      16:19                                                   *
 * Project:   php_blog_test                                           *
 **********************************************************************/

namespace Models;

use mysqli;
use mysqli_result;

/**
 * Database driver
 * automation of SELECT, INSERT, UPDATE and DELETE queries
 * could be used only for simple queries for one table
 * functions (methods) update(params) and delete(params) do not use
 * anywhere in the code, made for full functionality
 */
class DbDriver
{
    /**
     * Database driver instance
     *
     * @var DbDriver
     */
    private static $instance;

    /**
     * Database hostname
     *
     * @var string
     */
    private $hostname = 'localhost';

    /**
     * Database username
     *
     * @var string
     */
    private $username = 'root';

    /**
     * Password for database access
     *
     * @var string
     */
    private $password = 'root';

    /**
     * Database name
     *
     * @var string
     */
    private $dbName = 'blog_db';

    /**
     * Database port
     *
     * @var string
     *
     * @deprecated unused
     */
    private $port = '8889';

    /**
     * Connection to database
     *
     * @var $mysqli
     */
    private $mysqli;

    /**
     * Getting a single instance of class
     *
     * @return DbDriver
     */
    public static function getInstance(): DbDriver
    {
        return (self::$instance === null) ? self::$instance : new self();
    }

    /**
     * Db_Driver constructor.
     */
    private function __construct()
    {
        $this->mysqli = new mysqli($this->hostname, $this->username, $this->password);

        if ($this->mysqli->connect_errno) {
            die(sprintf('No connection with database! %s', $this->mysqli->connect_error));
        }

        $this->mysqli->query('SET NAMES utf8');
        $this->mysqli->set_charset('utf8');

        if (! $this->mysqli->select_db($this->dbName)) {
            self::createDB($this->dbName);
            $this->mysqli->select_db($this->dbName);
            self::createTable('articles');
            self::createTable('authors');
            self::createTable('images');
            self::fillTables();
        }
    }

    /**
     * SQL SELECT method
     *
     * @param string $query full text of SQL SELECT request
     *
     * @return array selected rows from database
     */
    public function select(string $query): array
    {
        $tmp = $this->mysqli->query($query);

        if (! $tmp) {
            die($this->mysqli->error);
        }

        $result = [];

        while ($row = $tmp->fetch_assoc()) {
            $result[] = $row;
            unset($row);
        }

        unset($tmp);

        if (! $result) {
            die($this->mysqli->error);
        }

        return $result;
    }

    /**
     * SQL INSERT method
     *
     * @param string $table  name of db table
     * @param array  $object associative array of pairs 'column_name - value'
     *
     * @return mixed auto generated ID of new row in table
     */
    public function insert(string $table, array $object)
    {
        $columns = [];
        $values  = [];

        foreach ($object as $key => $value) {
            $key       = $this->mysqli->real_escape_string($key . '');
            $columns[] = "`$key`";

            if (isset($value)) {
                $values[] = 'NULL';
            } else {
                $value    = $this->mysqli->real_escape_string($value . '');
                $values[] = "'$value'";
            }
            unset($key, $value);
        }

        $columnsString = implode(',', $columns);
        $valuesString  = implode(',', $values);

        unset($columns, $values);

        $sqlQuery = sprintf('INSERT INTO %s (%s) VALUES (%s)', $table, $columnsString, $valuesString);
        $result   = $this->mysqli->query($sqlQuery);

        unset($sqlQuery);

        if (! $result) {
            die($this->mysqli->error);
        }

        return $this->mysqli->insert_id;
    }

    /**
     * SQL UPDATE method
     *
     * @param string $table  name of db table
     * @param array  $object associative array of pairs 'column_name - value'
     * @param string $where  condition (a part of SQL request)
     *
     * @return int number of affected (changed) rows
     */
    public function update(string $table, array $object, string $where)
    {
        $sets = [];

        foreach ($object as $key => $value) {
            $key = $this->mysqli->real_escape_string($key . '');

            if ($value === null) {
                $sets[] = $value . '=NULL';
            } else {
                $value  = $this->mysqli->real_escape_string($value . '');
                $sets[] = sprintf('`%s`=\'%s\'', $key, $value);
            }

            unset($key, $value);
        }

        $setsString = implode(',', $sets);

        unset($sets);

        $sqlQuery = sprintf('UPDATE %s SET %s WHERE %s', $table, $setsString, $where);
        $result   = $this->mysqli->query($sqlQuery);

        unset($sqlQuery);

        if (! $result) {
            die($this->mysqli->error);
        }

        return $this->mysqli->affected_rows;
    }

    /**
     * SQL DELETE method
     *
     * @param string $table name of db table
     * @param string $where condition (a part of SQL request)
     *
     * @return int number of affected (deleted) rows
     */
    public function delete(string $table, string $where): int
    {
        $sqlQuery = sprintf('DELETE FROM %s WHERE %s', $table, $where);
        $result   = $this->mysqli->query($sqlQuery);

        unset($sqlQuery);

        if (! $result) {
            die($this->mysqli->error);
        }

        return $this->mysqli->affected_rows;
    }

    /**
     * Method for creation new database
     *
     * @param string $dbName new database name
     */
    private function createDB(string $dbName): void
    {
        $sqlQuery = sprintf('CREATE DATABASE %s', $dbName);
        $this->mysqli->query($sqlQuery);
        unset($sqlQuery);
    }

    /**
     * function (method) for creation tables (specialized)
     *
     * @param string $tableName name of table in database
     *
     * @return bool|mysqli_result SQL request for creation of the table
     */
    private function createTable(string $tableName): ?mysqli_result
    {
        switch ($tableName) {
            case ("articles"):
                $sqlQuery = <<<query
-- noinspection SqlDialectInspection

CREATE TABLE IF NOT EXISTS `articles` (
  `id_article` int(11) NOT NULL AUTO_INCREMENT,
  `id_author` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_img` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` varchar(1000) NOT NULL,
  PRIMARY KEY (`id_article`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
query;
                break;
            case ("authors"):
                $sqlQuery = <<<query
-- noinspection SqlDialectInspection

CREATE TABLE IF NOT EXISTS `authors` (
  `id_author` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id_author`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
query;
                break;
            case ("images"):
                $sqlQuery = <<<query
-- noinspection SqlDialectInspection

CREATE TABLE IF NOT EXISTS `images` (
  `id_img` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`id_img`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
query;
                break;
            default:
                die('You can\'t create such table! Only tables "articles", "authors" and "images" are required!');
        }

        return $this->mysqli->query($sqlQuery);
    }

    /**
     * Filling tables with content
     */
    private function fillTables()
    {
        for ($i = 1; $i <= 123; $i++) {
            // parameters of queries
            $idAuthor    = floor($i / 4 + 1);
            $imageNumber = ($i % 8 === 0) ? 8 : ($i % 8);
            $imagePath   = 'img/' . $imageNumber . '.jpg';
            $now         = date("Y-m-d H:i:s");
            $title       = 'Article #' . $i;
            $nameAuthor  = 'Author #' . $idAuthor;
            $emailAuthor = 'author' . $idAuthor . '@mail.com';
            $content     = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.'
                . ' Adipisci blanditiis deserunt, dolore exercitationem hic illum ipsam minus mollitia nam natus nisi,'
                . ' nobis pariatur quaerat quasi repudiandae soluta tempora tenetur unde.'
                . ' Culpa cum cupiditate eligendi,'
                . ' est et explicabo inventore ipsa magnam molestiae quibusdam quisquam quo, quos voluptatibus?'
                . ' Adipisci assumenda autem dolores inventore molestias perferendis quos saepe sed sint, sunt,'
                . ' tempore vel! A ab adipisci alias aspernatur assumenda, error esse est hic maiores nobis,'
                . ' non repellendus tempora tenetur! Ab animi commodi consequuntur,'
                . ' culpa cumque delectus dignissimos eos iste, quas recusandae repellendus vel?'
                . ' Accusamus aliquid amet aperiam asperiores atque autem commodi consectetur cumque dicta dolores'
                . ' exercitationem fuga impedit ipsam iure maiores nisi,'
                . ' odit perferendis possimus quae quidem repellendus saepe sit tempora veritatis voluptates?';

            // filling the 'articles' table
            $this->insert('articles', [
                'id_article' => $i,
                ''           => $idAuthor,
                'date'       => $now,
                'id_img'     => $i,
                'title'      => $title,
                'content'    => $content,
            ]);

            // filling the 'authors' table
            if ($i === 1 or $i % 4 === 0) {
                $this->insert('authors', [
                    'id_author' => $idAuthor,
                    'name'      => $nameAuthor,
                    'email'     => $emailAuthor,
                ]);
            }

            // filling the 'images' table
            $this->insert('images', [
                    'id_img' => $i,
                    'path'   => $imagePath,
                ]);

            unset($idAuthor, $imageNumber, $imagePath, $now, $title, $content, $nameAuthor, $emailAuthor);
        }
    }
}