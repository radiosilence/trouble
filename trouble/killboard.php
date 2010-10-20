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

import('core.container');
import('trouble.game');
import('trouble.kill');

class Killboard {
    private $game;
    public function attach_game(\Trouble\Game $game) {
        $this->game = $game;
    }
    public function get_data() {
        return Kill::mapper()->find_by_game($this->game);
    }
}

class KillboardContainer extends \Core\Container {
    public function get_game_killboard(\Trouble\Game $game) {
        $killboard = new Killboard();
        $killboard->attach_game($game);
        return $killboard;
    }
}
