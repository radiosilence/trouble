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

import('core.containment');
import('trouble.game');
import('trouble.kill');

class Killboard extends \Core\Contained {
    public function __construct() {
        $this->games = new \Core\Arr();
    }
    
    public function attach_game(\Trouble\Game $game) {
        $this->games->append($game);
        return $this;
    }

    public function load_data() {
        $this->games->map(function($game){
            $game->load_kills(True);

        });
        return $this;
    }
}

class KillboardContainer extends \Core\Container {
    public function get_game_killboard(\Trouble\Game $game) {
        $killboard = new Killboard();
        $killboard->attach_game($game);
        return $killboard;
    }
}
