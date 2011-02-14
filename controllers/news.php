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
import('plugins.articles.controller');
import('core.session.handler');
import('core.template');
import('core.backend');
import('core.storage');
import('core.security.antixsrf');

class News extends \Plugins\Articles\Controller {
    protected $_backend;
    protected $_session;
    protected $_template;
    protected $_antixsrf;

    public function __construct($args) {
        parent::__construct($args);
        $this->_init();
    }

    public function index() {
        $t = $this->_template;

        $t->articles = $this->_get_latest_articles();

        $t->content = $t->render('news.php');
        $t->title = 'News';
        echo $t->render('main.php');
    }

    public function display_article() {
        $t = $this->_template;
        $articles = $this->_get_article($this->_args['article_id']);
        $t->article = $articles[0];

        $t->content = $t->render('news_article.php');
        $t->title = $article->title;
        echo $t->render('main.php');
    }
    protected function _init() {
        $this->_init_backend();
        $this->_init_session();
        $this->_antixsrf = \Core\Security\AntiXSRF::create($args['__antixsrf_reqid__'])
            ->attach_session($this->_session);
        $this->_init_template();
        $this->_init_storage();
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