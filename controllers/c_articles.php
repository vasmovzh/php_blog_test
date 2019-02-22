<?php
/**
 * articles controller
 */

class C_Articles extends C_Base {
    // main blog page
    public function actionIndex() {
        $articles = $this->m_articles->previewArticles($this->getCurrentPage());
        $this->title .= "::Articles";
        $navigator = $this->template("views/v_nav.php",
            array('page' => $this->getCurrentPage(), 'pages' => $this->pages));
        $this->content = $this->template("views/v_index.php",
            array('articles' => $articles, 'navigator' => $navigator));
    }

    // article view
    public function actionArticleView() {
        $article = $this->m_articles->getArticle($_GET['id'])[0];
        $this->title .= "::".$article['title'];
        $this->content = $this->template("views/v_article.php", array('article' => $article));
    }
}