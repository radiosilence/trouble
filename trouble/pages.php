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
import('core.template');
import('core.containment.pdo');

abstract class StandardPage extends \Core\Controller {
    protected $pdo;
    protected $session;
    protected $template;
   
    public function __construct($args) {
        parent::__construct($args);
        $this->init_pdo();
        $this->init_session();
    }

    protected function init_session() {
        $this->session = \Core\Session\Handler::container(array(
                'pdo' => $this->pdo
            ))
            ->get_standard_session();
    }

    protected function init_pdo() {
        $c = new \Core\Containment\PDOContainer();
        $this->pdo = $c->get_connection();
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
        $this->game = Game::mapper()
            ->attach_pdo($this->pdo)
            ->find_by_id($this->args['game_id']);
    }
}

abstract class AgentPage extends StandardPage {
    protected $agent;

    protected function init_agent($alias) {
        $this->agent = \Trouble\Agent::mapper()
            ->attach_pdo($this->pdo)
            ->find_by('alias', $alias);
    }
}