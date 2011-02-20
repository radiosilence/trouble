<?php

/*
 * This file is part of the core framework.
 *
 * (c) James Cleveland <jamescleveland@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Controllers;

import('core.exceptions');
import('core.session.handler');
import('core.controller');
import('core.template');
import('core.backend');
import('core.storage');
import('core.security.antixsrf');
import('trouble.agent');

abstract class StandardPage extends \Core\Controller {
    protected $_backend;
    protected $_session;
    protected $_template;
    protected $_antixsrf;
    protected $_user;

    public function __construct($args=False) {
        parent::__construct($args);
        $this->_init_backend();
        $this->_init_session();
        $this->_init_template();

        if(isset($this->_session['auth'])) {
            $this->_init_user();
        }
    }

    protected function _init_user() {
        $t = $this->_template;
        $this->_user = \Trouble\Agent::mapper()
            ->attach_storage(\Core\Storage::container()
                ->get_storage('Agent')
            )
            ->create_object($this->_session['auth']['data']);
        $t['user'] = $this->_user;
        $t['_user_box'] = $t->render('user_box.php');
    }

    protected function _init_template() {
        $t = \Core\Template::create();
        $t['__uri__'] = $this->_args['__uri__']; 
        $t['_user_box'] = $t->render('login_box.php');
        $t->_jsapps = array();
        $this->_template = $t;
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
}

abstract class GamePage extends StandardPage {
    protected $_game;
    
    public function __construct($args) {
        import('trouble.game');
        parent::__construct($args);
        $this->_init_game();
        $this->_template->add('_jsapps', 'games');
    }
    private function _init_game() {
        $this->game_storage = \Core\Storage::container()
            ->get_storage('Game');
        $games = \Trouble\Game::mapper()
            ->attach_storage($this->game_storage)
            ->get_list(array(
                'filter' => new \Core\Filter('id', $this->_args['game_id'])
            ));
        $this->_game = $games[0];
    }
}

abstract class AgentPage extends StandardPage {
    protected $agent;

    protected function _init_agent($alias) {
        $this->agent_storage = \Core\Storage::container()
            ->get_storage('Agent');
        $this->agent = \Trouble\Agent::mapper()
            ->attach_storage($this->agent_storage)
            ->find_by('alias', $alias);
    }
}
