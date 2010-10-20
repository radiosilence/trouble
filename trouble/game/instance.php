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

class Instance extends \Core\Superclass\PDODependent {
    private $loaded = False;

    public function load($id) {
        $sth = $this->pdo->prepare( "
            SELECT *
            FROM games
            WHERE id = :id
        ");

        $sth->execute(array(
            ':id' => $id
        ));
        $this->data = $sth->fetchObject();
        $this->loaded = True;
    }

    public function is_loaded() {
        return $this->loaded;
    }
}
?>
