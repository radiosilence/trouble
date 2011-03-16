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

import('controllers.standard_page');
import('core.validation');

class Agent extends \Controllers\AgentPage {
    public function index() {
        $this->_init_agent($this->_args['alias']);
        $t = $this->_template;
        $t->agent = $this->_agent;
        $t->title = $this->_agent['alias'];
        $t->content = $t->render('agent.php');
        echo $t->render('main.php');
    }

    /**
     * TODO: Some form of authentication!
     */
    public function edit() {
        $t = $this->_template;
        $t->add('_jsapps', 'agent_form');

        $storage = \Core\Storage::container()
            ->get_storage('Agent');
        $mapper = \Trouble\Agent::mapper()
            ->attach_storage($storage);
        $t->errors = array();
        if($this->_args['alias']) {
            $t->title = "Edit Agent";
            $agent = \Trouble\Agent::container()
                ->get_by_alias($this->_args['alias']);
        } else if($this->_session['auth']) {
            $t->title = "Edit Yourself";
            $agent = \Trouble\Agent::container()
                ->get_by_id($this->_auth->user_id());
        } else {
            $t->title = "Agent Application";
            $t->new = True;
            $agent = \Trouble\Agent::create();
        }

        $t->agent = $agent;
        $t->content = $t->render('forms/agent.php');
        echo $t->render('main.php');
    }
/*
    public function async_validate() {
        $validator = \Core\Validator::validator()
            ->attach_mapper('agent', \Trouble\Agent::mapper()
                ->attach_pdo($this->pdo));
        try {
            $validator->validate(
                $_POST,
                \Trouble\Agent::validation(),
                $this->args['field']
            );
            $errors = null;
        } catch(\Core\ValidationError $e) {
            $errors = $e->get_errors();
        }
        echo json_encode($errors);
    }*/
}
