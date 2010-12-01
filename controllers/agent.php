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
class Agent extends \Trouble\AgentPage {
    public function index() {
        $this->init_agent($this->args['alias']);
        $t = new \Core\Template();
        $t->agent = $this->agent;
        $t->content = $t->render('agent.php');

        echo $t->render('main.php');
    }

    public function create() {
        $agent = \Trouble\Agent::mapper()
        ->attach_pdo($this->pdo);
        $t = new \Core\Template();
        $t->new = True;
        $t->content = $t->render('forms/agent.php');
        echo $t->render('main.php');
    }
}
