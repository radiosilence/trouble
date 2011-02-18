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
import('core.backend');
import('core.storage');
import('core.security.antixsrf');

abstract class StandardPage extends \Core\Controller {
    protected $_backend;
    protected $_session;
    protected $_template;
    protected $_antixsrf;

    public function __construct($args) {
        parent::__construct($args);
        $this->_init_backend();
        $this->_init_session();
        $this->_antixsrf = \Core\Security\AntiXSRF::create($args['__antixsrf_reqid__'])
            ->attach_session($this->_session);
            
        if($args['__antixsrf__']) {
            $this->_antixsrf->check();
        }
        $this->_init_template();
    }

    protected function _init_template() {
        $t = \Core\Template::create()
            ->attach_util("antixsrf", $this->_antixsrf);
        $t['__uri__'] = $this->_args['__uri__']; 
        if(isset($this->_session['auth'])) {
            $t['_user_box'] = $t->render('user_box.php');
            $t['_user_data'] = $this->_session['auth']['data'];
        } else {
            $t['_user_box'] = $t->render('login_box.php');        
        }
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
    protected $game;
    
    public function __construct($args) {
        import('trouble.game');
        parent::__construct($args);
        $this->_init_game();
    }
    private function _init_game() {
        $this->game_storage = \Core\Storage::container()
            ->get_storage('Game');
        $this->game = Game::mapper()
            ->attach_storage($this->game_storage)
            ->get_list(new \Core\Dict(array(
                'filter' => new \Core\Filter('id', $this->args['game_id'])
            )));
        $this->game = $this->game[0];

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
