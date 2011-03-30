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
import('core.auth');
import('core.security.antixsrf');
import('trouble.agent');

abstract class StandardPage extends \Core\Controller {
    protected $_backend;
    protected $_session;
    protected $_template;
    protected $_antixsrf;
    protected $_user;
    protected $_auth;

    public function __construct($args=False) {
        parent::__construct($args);
        $this->_init_session();
        $this->_init_template();
        $this->_init_auth();
        try {
            $this->_template->logged_in = True;
            $this->_t_user_box();
        } catch(\Core\AuthNotLoggedInError $e){
            $this->_template->logged_in = False;
            $this->_t_login_box();
        }
    }

    protected function _init_auth() {
        $this->_auth = \Core\Auth::container()
            ->get_auth('agent', $this->_session, array(
                'user_field' => 'alias'
            ));
    }
    protected function _t_user_box() {
        $t = $this->_template;
        $this->_user = \Trouble\Agent::mapper()
            ->create_object($this->_auth->user_data());
        $t['user'] = $this->_user;
        $t['_user_box'] = $t->render('user_box.php');
    }

    protected function _t_login_box() {
        $t = $this->_template;
        $t['_user_box'] = $t->render('login_box.php');
    }

    protected function _init_template() {
        $t = \Core\Template::create();
        $t['__uri__'] = $this->_args['__uri__']; 
        $t->_jsapps = array();
        $this->_template = $t;
    }


    protected function _init_session() {
        $this->_session = \Core\Session\Handler::container()
            ->get_hs_session();
    }

    protected function _current_user() {
        if(isset($this->_session['auth'])) {
            return $this->_session['auth']['id'];
        } else {
            
        }
    }
}

abstract class GamePage extends StandardPage {
    protected $_game;
    
    public function __construct($args) {
        parent::__construct($args);
        import('trouble.game');
        $this->_init_game();
        $this->_template->add('_jsapps', 'games');
    }
    private function _init_game() {
        if(!$this->_args['game_id']) {
            return False;
        }
        try {
            $this->_game = \Trouble\Game::container()
                ->get_by_id($this->_args['game_id']);
        } catch(\Trouble\GameNotFoundError $e) {
            throw new \Core\HTTPError(404, "Game #{$this->_args[game_id]}");
        }

    }
}

abstract class AgentPage extends StandardPage {
    protected $_agent;
    public function __construct($args) {
        parent::__construct($args);
        import('trouble.agent');
    }
    protected function _init_agent($alias) {
        $this->_agent = \Trouble\Agent::container()
            ->get_by_alias($alias);
    }
}
