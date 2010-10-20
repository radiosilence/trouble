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

import('trouble.killboard.instance');
import('trouble.weapon');
import('core.container');

class Container extends \Core\Container {
    public function get_game_killboard(\Trouble\Game\Instance $game) {
        $killboard = new Instance();
        $killboard->attach_game($game);
        return $killboard;
    }
}

?>
