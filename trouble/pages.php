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
import('core.database.container');
import('core.session.container');
import('core.controller');
import('core.view');

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
        $c_session = new \Core\Session\Container(array(
                'pdo' => $this->pdo
        ));
        $this->session = $c_session->get_standard_session();
    }

    protected function init_db() {
        $c_database = new \Core\Database\Container();
        $this->pdo = $c_database->get_pdo();
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
        $game_c = new \Trouble\GameContainer(array(
            'pdo' => $this->pdo
        ));
        $this->game = $game_c->get_game($this->args['game']);
    }
}
