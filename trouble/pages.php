<?php

/*
 * This file is part of the core framework.
 *
 * (c) James Cleveland <jamescleveland@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Trouble;

import('core.exceptions');
import('core.session.handler');
import('core.controller');
import('core.view');
import('core.database.pdo');

abstract class StandardPage extends \Core\Controller {
    protected $pdo;
    protected $session;
    protected $view;
   
    public function __construct($args) {
        parent::__construct($args);
        $this->init_db();
        $this->init_session();
        $this->view = new \Core\View();
    }

    protected function init_session() {
        if(!($this->pdo instanceof \PDO)) {
            throw new \Core\Error("Trying to initialize a session without a db.");
        }
        $this->session = \Core\Session\Handler::container(array(
                'pdo' => $this->pdo
        ))->get_standard_session();
    }

    protected function init_db() {
        $this->pdo = \Core\Database\PDOContainer::get_default_pdo();
    }
}

abstract class GamePage extends StandardPage {
    protected $game;
    public function __construct($args) {
        import('trouble.game');
        parent::__construct($args);
        $this->init_game();
    }
    private function init_game() {
        $this->game = Game::mapper()->find_by_id($this->args['game_id']);
    }
}
