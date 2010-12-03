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
        $this->init_agent($this->args['alias']);
        $t = new \Core\Template();
        $t->agent = $this->agent;
        $t->content = $t->render('agent.php');

        echo $t->render('main.php');
    }

    /**
     * TODO: Some form of authentication!
     */
    public function edit() {
        $t = new \Core\Template();
        $t->title = "Agent Application";
        $mapper = \Trouble\Agent::mapper()
            ->attach_pdo($this->pdo);

        if($this->args['alias']) {
            $agent = $mapper
                ->find_by('alias', $this->args['alias']);
        } else {
            $t->new = True;
            $agent = new \Trouble\Agent();
        }

        if (isset($_POST['submitted'])) {
            $validator = \Core\Validator::validator()
                ->attach_mapper('agent', $mapper);
            if(!$t->new) {
                $validator->set_id($agent->id);
            }
            try {
                $validator->validate($_POST, \Trouble\Agent::validation());
                $agent->update($_POST,
                    array('exclude' => array('alias'))
                );
                // Authentication here. If currently logged in
                // user can update agent of alias x
                $mapper
                    ->save($agent);
            
            } catch(\Core\ValidationError $e) {
                $errors = $e->get_errors();
                print_r($errors);
            } catch(PDOException $e) {
                throw new \Core\Error(sprintf('Database Error: %s',
                    $e->getMessage()));
            }
        }
        $t->agent = $agent;
        $t->content = $t->render('forms/agent.php');
        echo $t->render('main.php');
    }

    public function ajax_validate() {
        
    }
}
