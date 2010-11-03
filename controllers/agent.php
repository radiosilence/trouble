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
import('core.types');
class Agent extends \Trouble\AgentPage {
    public function index() {
        $t = new \Core\Template();
        $t->agent = $this->agent;
        $t->content = $t->render('agent.php');

        echo $t->render('main.php');
    }
}
