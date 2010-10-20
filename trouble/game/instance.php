<?php

/*
 * This file is part of the core framework.
 *
 * (c) James Cleveland <jamescleveland@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Trouble\Game;

import('core.superclass.pdo');

class Instance extends \Core\Superclass\PDOStored {
    protected static $table = 'games';

    private $kills;
            
    public function load_kills() {
        \Trouble\KILL::attach_pdo(static::$pdo);
        $kill_ids = array(1,2,3);
        \Trouble\KILL::populate_cache($kill_ids);
        $kill = new \Trouble\Kill();
    }
    
    private function populate_kill_ids() {
    
    }
    
    public function get_kills() {
    }

}
?>
