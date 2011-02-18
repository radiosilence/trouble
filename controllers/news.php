<?php

/*
 * This file is part of trouble.
 *
 * (c) James Cleveland <jamescleveland@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Controllers;

import('core.types');
import('plugins.articles.article');
import('controllers.standard_page');

class News extends \Controllers\StandardPage {

    public function index() {
        $t = $this->_template;

        $t->articles = \Plugins\Articles\Article::mapper()
            ->attach_storage(\Core\Storage::container()
                ->get_storage('Article')
            )
            ->get_latest_articles();

        $t->content = $t->render('news.php');
        $t->title = 'News';
        echo $t->render('main.php');
    }

    public function display_article() {
        $t = $this->_template;
        $t->article = \Plugins\Articles\Article::mapper()
            ->attach_storage(\Core\Storage::container()
                ->get_storage('Article')
            )
            ->get_article($this->_args['article_id']);

        $t->content = $t->render('news_article.php');
        $t->title = $article->title;
        echo $t->render('main.php');
    }

    protected function _init_session() {
        $this->_session = \Core\Session\Handler::container(array(
                'pdo' => $this->_backend
            ))
            ->get_standard_session();
    }
    protected function _init_backend() {
        $this->_backend = \Core\Backend::container()
            ->get_backend();
    }
    protected function _init_template() {
        $this->_template = \Core\Template::create()
            ->attach_util("antixsrf", $this->_antixsrf);
    }
}