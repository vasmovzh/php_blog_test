<?php
/**********************************************************************
 * @author    Valeriy A. Smovzh aka vasmovzh (r) <vasmovzh@yandex.ru> *
 * @license   GNU AGPLv3                                              *
 * @copyright Copyright (c) 2019, vasmovzh (r)                        *
 * Date:      02.06.2019                                              *
 * Time:      22:14                                                   *
 * Project:   php_blog_test                                           *
 **********************************************************************/

namespace Controllers;

use Models\MArticles;

/**
 * Abstract base site controller
 */
abstract class CBase extends CController
{
    /**
     * Tab title
     *
     * @var string
     */
    protected $title;

    /**
     * Page content
     *
     * @var string
     */
    protected $content;

    /**
     * Articles model
     *
     * @var MArticles
     */
    protected $mArticles;

    /**
     * Number of pages
     *
     * @var float
     */
    public $pages;

    /**
     * CBase constructor
     */
    public function __construct()
    {
        $this->mArticles = MArticles::getInstance();
        $this->pages     = $this->mArticles->pagesCount();
    }

    /**
     * Function (method) that works before the main method
     *
     * @return mixed|void
     */
    protected function before()
    {
        $this->title   = 'Simple Blog';
        $this->content = '';
    }

    /**
     * External template generation method
     *
     * @return mixed|void
     */
    protected function render()
    {
        $params = ['title' => $this->title, 'content' => $this->content];
        $html   = $this->template("Views/v_main.php", $params);
        echo $html;
    }

    /**
     * Method returns current page number
     *
     * @return int
     */
    protected function getCurrentPage(): int
    {
        if (isset($_GET['page']) and $_GET['page'] !== 0 and $_GET['page'] > 0) {
            $currentPage = (int) $_GET['page'];

            if ($currentPage > $this->pages) {
                $this->redirect($_SERVER['PHP_SELF']);
            }
        } else {
            $currentPage = 1;
        }

        return $currentPage;
    }
}
