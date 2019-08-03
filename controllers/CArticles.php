<?php
/**********************************************************************
 * @author    Valeriy A. Smovzh aka vasmovzh (r) <vasmovzh@yandex.ru> *
 * @license   GNU AGPLv3                                              *
 * @copyright Copyright (c) 2019, vasmovzh (r)                        *
 * Date:      02.06.2019                                              *
 * Time:      22:17                                                   *
 * Project:   php_blog_test                                           *
 **********************************************************************/

namespace Controllers;

/**
 * Articles model
 */
class CArticles extends CBase
{
    /**
     * Main blog page method. Returns main page with paginator
     */
    public function actionIndex()
    {
        $articles      = $this->mArticles->previewArticles($this->getCurrentPage());
        $this->title   .= "::Articles";
        $navigator     = $this->template(
            "Views/v_nav.php",
            ['page' => $this->getCurrentPage(), 'pages' => $this->pages]
        );
        $this->content = $this->template(
            "Views/v_index.php",
            ['articles' => $articles, 'navigator' => $navigator]
        );
    }

    /**
     * Method returns article view template
     */
    public function actionArticleView()
    {
        $article       = $this->mArticles->getArticle($_GET['id'])[0];
        $this->title   .= '::' . $article['title'];
        $this->content = $this->template('Views/v_article.php', ['article' => $article]);
    }
}
