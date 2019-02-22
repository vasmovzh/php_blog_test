<?php
/**
 * articles model
 */

class M_Articles extends M_Base {
    private static $instance;

    // returns a single instance
    public static function getInstance() {
        if (self::$instance == null)
            self::$instance = new self();

        return self::$instance;
    }

    // returns count of articles in database
    public function count() {
        $sql_query = "SELECT COUNT(*) AS cnt FROM articles";
        return $this->mysqli->select($sql_query)[0]['cnt'];
    }

    // returns number of pages for pagination
    public function pages() {
        return ceil($this->count() / APP);
    }

    // returns only one article with full content
    public function getArticle($id) {
        $id = (int) $id;
        $sql_query = "SELECT * FROM articles JOIN authors USING(id_author)
                      JOIN images USING(id_img) WHERE `id_article`={$id};";
        unset($id);
        return $this->mysqli->select($sql_query);
    }

    public function previewArticles($page, $app = APP) {
        $articles = array();
        $offset = ($page - 1) * $app;
        $sql_query = "SELECT * FROM articles JOIN authors USING(id_author)
                      JOIN images USING(id_img) ORDER BY `id_article` DESC LIMIT $offset, $app;";
        $tmp = $this->mysqli->select($sql_query);
        unset($offset, $page, $app, $sql_query);

        foreach ($tmp as $row) {
            if (mb_strlen($row['content']) > PREVIEW) {
                $row['content'] = mb_strimwidth($row['content'], 0, PREVIEW, "...");
                $row['readmore'] = true;
            }
            else $row['readmore'] = false;

            $articles[] = $row;

            unset($row);
        }
        return $articles;
    }
}