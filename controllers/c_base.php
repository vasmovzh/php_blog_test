<?php
/**
 * abstract base site controller
 */

abstract class C_Base extends C_Controller {
    protected $title;   // tab title
    protected $content; // page content
    protected $m_articles; // articles model
    public $pages;

    public function __construct() {
        $this->m_articles = M_Articles::getInstance();
        $this->pages = $this->m_articles->pages();
    }

    protected function before() {
        $this->title = "Simple Blog";
        $this->content = "";
    }

    // base template generation
    public function render() {
        $params = array('title' => $this->title, 'content' => $this->content);
        $html = $this->template("views/v_main.php", $params);
        echo $html;
    }

    // clear from function (method) name
    protected function getCurrentPage() {
        if (isset($_GET['page']) and $_GET['page'] !== 0 and $_GET['page'] > 0) {
            $current_page = (int) $_GET['page'];

            if ($current_page > $this->pages) {
                $this->redirect($_SERVER['PHP_SELF']);
            }
        }
        else $current_page = 1;

        return $current_page;
    }
}