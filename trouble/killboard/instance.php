<?php

/*
 * This file is part of the core framework.
 *
 * (c) James Cleveland <jamescleveland@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Trouble\Killboard;

import('core.superclass.pdo');
import('trouble.game.instance');

class Instance extends \Core\Superclass\PDODependent {
    private $game;
    public function attach_game(\Trouble\Game\Instance $game) {
        $this->game = $game;
    }
    
    public function load_kills() {
        
    }
    
    public function get_kills() {
        $kill_ids = array(1,2,3);
        $kill = new kill();
    }
}
