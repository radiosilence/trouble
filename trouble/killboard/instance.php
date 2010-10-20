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

import('core.superclasses');
import('trouble.game.instance');

class Instance extends \Core\PDODependentClass {
    private $game;
    public function attach_game(\Trouble\Game\Instance $game) {
        $this->game = $game;
    }
}