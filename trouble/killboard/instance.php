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

import('trouble.game.instance');
import('trouble.kill');
class Instance {
    private $game;
    public function attach_game(\Trouble\Game\Instance $game) {
        $this->game = $game;
    }
    public function get_data() {
        $this->game->load_kills();
    }
}
