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

import('trouble.weapon');
import('trouble.pages');
import('trouble.killboard');
import('trouble.agent');
import('core.types');
import('core.validation');

class Agent extends \Trouble\AgentPage {
    public function index() {
        $this->_init_agent($this->args['alias']);
        $t = new \Core\Template();
        echo $this->__fullname__();
        $t->agent = $this->agent;
        $t->content = $t->render('agent.php');
        echo $t->render('main.php');
    }

    /**
     * TODO: Some form of authentication!
     */
    public function edit() {
        $t = new \Core\Template();
        
        $storage = \Core\Storage::container()
            ->get_storage('Agent');
        $mapper = \Trouble\Agent::mapper()
            ->attach_storage($storage);
        $t->errors = array();
        if($this->args['alias']) {
            $t->title = "Edit Agent";
            $agent = $mapper
                ->find_by('alias', $this->args['alias']);
        } else {
            $t->title = "Agent Application";
            $t->new = True;
            $agent = new \Trouble\Agent();
        }

        if (isset($_POST['submitted'])) {
            if(!$t->new) {
                $validator->set_id($agent->id);
                $_POST['alias'] = $agent->alias;
            }
            try {
                $validator->validate($_POST, \Trouble\Agent::validation());
                $agent->update($_POST);
                // Authentication here. If currently logged in
                // user can update agent of alias x
                $mapper
                    ->save($agent);
            
            } catch(\Core\ValidationError $e) {
                $t->errors = $e->get_errors();
            } catch(PDOException $e) {
                throw new \Core\Error(sprintf('Database Error: %s',
                    $e->getMessage()));
            }
        }
        $t->agent = $agent;
        $t->content = $t->render('forms/agent.php');
        echo $t->render('main.php');
    }

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
    }
}
