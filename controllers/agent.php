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
import('trouble.intel');

class Agent extends \Controllers\AgentPage {
    public function index() {
        $this->_init_agent($this->_args['alias']);
        $t = $this->_template;
        $t->title = $this->_agent['alias'];
        $tabs = array();
        try {
            $tabs['edit'] = array(
                'title' => 'Edit',
                'content' => $this->_edit_agent()
            );
        } catch(\Core\AuthError $e) {}
        $t->tabs = $tabs;
        $t->content = $t->render('tabs.php');
        echo $t->render('main.php');
    }

    public function edit() {
        $t = $this->_template;
        $t->content = $this->_edit_agent();
        echo $t->render('main.php');
    }

    protected function _edit_agent() {
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
            $this->_auth->check_admin('agent', $agent->id);
        } else {
            try{
                $t->title = "Edit Yourself";
                $agent = \Trouble\Agent::container()
                    ->get_by_id($this->_auth->user_id());                
            } catch(\Core\AuthNotLoggedInError $e) {
                $t->title = "Agent Application";
                $t->new = True;
                $agent = \Trouble\Agent::create();
            }
        }
        $t->agent = $agent;
        return $t->render('forms/agent.php');
    }
}
