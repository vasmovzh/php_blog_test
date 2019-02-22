<?php

/**
 * database driver
 * automation of SELECT, INSERT, UPDATE and DELETE queries
 * could be used only for simple queries for one table
 * functions (methods) update(params) and delete(params) do no use
 * anywhere in the code, made for full functionality
 */

class DB_Driver {
    private static $instance;

    // db settings
    private $hostname = 'localhost';
    private $username = 'root';
    private $password = 'root';
    private $db_name = 'blog_db';
    private $port = '8889';

    private $mysqli;

    // returns a single instance
    public static function getInstance() {
        if (self::$instance === null)
            self::$instance = new self();

        return self::$instance;
    }

    private function __construct() {
        $this->mysqli = new mysqli($this->hostname, $this->username, $this->password);

        if ($this->mysqli->connect_errno)
            die("No connection with database! {$this->mysqli->connect_error}");

        $this->mysqli->query('SET NAMES utf8');
        $this->mysqli->set_charset('utf8');
//        $this->mysqli->select_db($this->db_name) or
//            die("Error! No database! First, execute SQL-queries in file \"blog_db_vasmovzh.sql\"");
        // if db not exists
        if (!$this->mysqli->select_db($this->db_name)) {
            self::create_db($this->db_name);
            $this->mysqli->select_db($this->db_name);
            self::create_table("articles");
            self::create_table("authors");
            self::create_table("images");
            self::fill_tables();
        }
    }

    /**
     * @param $query    - full text of SQL SELECT request
     * @return array    - @param $result selected objects (rows) from db
     */
    public function select($query) {
        $tmp = $this->mysqli->query($query);

        if (!$tmp) die($this->mysqli->error);

        $result = array();

        while ($row = $tmp->fetch_assoc()) {
            $result[] = $row;
            unset($row);
        }

        unset($tmp);

        if (!$result) die($this->mysqli->error);

        return $result;
    }

    /**
     * @param $table    - name of db table
     * @param $object   - associative array of pairs "column_name - value"
     * @return mixed    - auto generated ID of new row in table
     */
    public function insert($table, $object) {
        $columns = array();
        $values = array();

        foreach ($object as $key => $value) {
            $key = $this->mysqli->real_escape_string($key . '');
            $columns[] = "`$key`";

            if ($value === null) $values[] = 'NULL';
            else {
                $value = $this->mysqli->real_escape_string($value . '');
                $values[] = "'$value'";
            }
            unset($key, $value);
        }

        $columns_s = implode(",", $columns);
        $values_s = implode(",", $values);

        unset($columns, $values);

        $sql_query = "INSERT INTO $table ($columns_s) VALUES ($values_s)";
        $result = $this->mysqli->query($sql_query);

        unset($sql_query);

        if (!$result) die($this->mysqli->error);

        return $this->mysqli->insert_id;
    }

    /**
     * @param $table    - name of db table
     * @param $object   - associative array of pairs "column_name - value"
     * @param $where    - condition (a part of SQL request)
     * @return int      - number of affected (changed) rows
     */
    public function update($table, $object, $where) {
        $sets = array();

        foreach ($object as $key => $value) {
            $key = $this->mysqli->real_escape_string($key . '');
            if ($value === null) $sets[] = "$value=NULL";
            else {
                $value = $this->mysqli->real_escape_string($value . '');
                $sets[] = "`$key`='$value'";
            }
            unset($key, $value);
        }

        $sets_s = implode(",", $sets);

        unset($sets);

        $sql_query = "UPDATE $table SET $sets_s WHERE $where";
        $result = $this->mysqli->query($sql_query);

        unset($sql_query);

        if (!$result) die($this->mysqli->error);

        return $this->mysqli->affected_rows;
    }

    /**
     * @param $table    - name of db table
     * @param $where    - condition (a part of SQL request)
     * @return int      - number of affected (deleted) rows
     */
    public function delete($table, $where) {
        $sql_query = "DELETE FROM $table WHERE $where";
        $result = $this->mysqli->query($sql_query);

        unset($sql_query);

        if (!$result) die($this->mysqli->error);

        return $this->mysqli->affected_rows;
    }

    /**
     * @param $db_name  - name of new database
     */
    private function create_db($db_name) {
        $sql_query = "CREATE DATABASE {$db_name}";
        $this->mysqli->query($sql_query);
        unset($sql_query);
    }

    /**
     * function (method) for creation tables (specialized)
     * @param $table_name           - name of table in db
     * @return bool|mysqli_result   - SQL request for creation of the table
     */
    private function create_table($table_name) {
        switch ($table_name) {
            case("articles"):
                $sql_query = <<<query
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
            case("authors"):
                $sql_query = <<<query
CREATE TABLE IF NOT EXISTS `authors` (
  `id_author` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id_author`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
query;
                break;
            case("images"):
                $sql_query = <<<query
CREATE TABLE IF NOT EXISTS `images` (
  `id_img` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`id_img`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
query;
                break;
            default:
                die("You can't create such table! Only tables 'articles', 'authors' and 'images' are required!");
        }
        return $this->mysqli->query($sql_query);
    }

    private function fill_tables() {
        for ($i = 1; $i <= 123; $i++) {
            // parameters of queries
            $id_author = floor($i / 4 + 1);
            $img_num = ($i % 8 === 0) ? 8 : ($i % 8);
            $img_path = "img/$img_num.jpg";
            $now = date("Y-m-d H:i:s");
            $title = "Article #$i";
            $name_author = "Author #$id_author";
            $email_author = "author$id_author@mail.com";
            $content = "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci blanditiis deserunt, dolore exercitationem hic illum ipsam minus mollitia nam natus nisi, nobis pariatur quaerat quasi repudiandae soluta tempora tenetur unde. Culpa cum cupiditate eligendi, est et explicabo inventore ipsa magnam molestiae quibusdam quisquam quo, quos voluptatibus? Adipisci assumenda autem dolores inventore molestias perferendis quos saepe sed sint, sunt, tempore vel! A ab adipisci alias aspernatur assumenda, error esse est hic maiores nobis, non repellendus tempora tenetur! Ab animi commodi consequuntur, culpa cumque delectus dignissimos eos iste, quas recusandae repellendus vel? Accusamus aliquid amet aperiam asperiores atque autem commodi consectetur cumque dicta dolores exercitationem fuga impedit ipsam iure maiores nisi, odit perferendis possimus quae quidem repellendus saepe sit tempora veritatis voluptates?";

            // fill the "articles"
            self::insert("articles",
                array("id_article" => "$i",
                    "id_author" => "$id_author",
                    "date" => "$now",
                    "id_img" => "$i",
                    "title" => "$title",
                    "content" => "$content"));

            // fill the "authors"
            if ($i === 1 or $i % 4 === 0) {
                self::insert("authors",
                    array("id_author" => "$id_author",
                        "name" => "$name_author",
                        "email" => "$email_author"));
            }

            // fill the "images"
            self::insert("images",
                array("id_img" => "$i",
                    "path" => "$img_path"));

            unset($id_author, $img_num, $img_path, $now, $title, $content, $name_author, $email_author,
                $q_for_art, $q_for_auth, $q_for_img);
        }
    }
}