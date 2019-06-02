<?php
/**********************************************************************
 * @author    Valeriy A. Smovzh aka vasmovzh (r) <vasmovzh@yandex.ru> *
 * @license   GNU AGPLv3                                              *
 * @copyright Copyright (c) 2019, vasmovzh (r)                        *
 * Date:      02.06.2019                                              *
 * Time:      21:17                                                   *
 * Project:   php_blog_test                                           *
 **********************************************************************/

namespace Models;

/**
 * Articles model class
 */
class MArticles extends MBase
{
    /**
     * Articles model instance
     *
     * @var MArticles
     */
    private static $instance;

    /**
     * Getting a single instance of class
     *
     * @return MArticles
     */
    public static function getInstance()
    {
        return self::$instance === null ? self::$instance : new self();
    }

    /**
     * Counter of articles inn database method
     *
     * @return int count of articles in database
     */
    public function articlesCount(): int
    {
        $sqlQuery = 'SELECT COUNT(*) AS cnt FROM articles';

        return $this->mysqli->select($sqlQuery)[0]['cnt'];
    }

    /**
     * Counter of pages for pagination
     *
     * @return float number of pages
     */
    public function pagesCount()
    {
        return ceil($this->articlesCount() / ARTICLES_PER_PAGE);
    }

    /**
     * Method returns one article with full content
     *
     * @param int $id article id
     *
     * @return array
     */
    public function getArticle(int $id): array
    {
        $id       = (int) $id;
        $sqlQuery = sprintf(
            'SELECT * FROM articles JOIN authors USING(id_author)'
            . ' JOIN images USING(id_img) WHERE `id_article`=%s',
            $id
        );
        unset($id);

        return $this->mysqli->select($sqlQuery);
    }

    /**
     * Method returns all articles on the web page
     *
     * @param int $page            number of page
     * @param int $articlesPerPage number of articles per page
     *
     * @return array articles
     */
    public function previewArticles(int $page, int $articlesPerPage = ARTICLES_PER_PAGE)
    {
        $articles = [];
        $offset   = ($page - 1) * $articlesPerPage;
        $sqlQuery = sprintf(
            'SELECT * FROM articles JOIN authors USING(id_author)'
            . 'JOIN images USING(id_img) ORDER BY `id_article` DESC LIMIT %d, %d',
            $offset,
            $articlesPerPage
        );
        $tmp      = $this->mysqli->select($sqlQuery);
        unset($offset, $page, $articlesPerPage, $sqlQuery);

        foreach ($tmp as $row) {
            if (mb_strlen($row['content']) > CHARS_FOR_PREVIEW) {
                $row['content']  = mb_strimwidth($row['content'], 0, CHARS_FOR_PREVIEW, "...");
                $row['readmore'] = true;
            } else {
                $row['readmore'] = false;
            }

            $articles[] = $row;

            unset($row);
        }

        return $articles;
    }
}