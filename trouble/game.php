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
import('core.superclass.mapping');

class Game extends \Core\Superclass\Mapped {
    private $kills;
    
    private function populate_kill_ids() {
    
    }
    
    public function get_kills() {
    }

}

class GameMapper extends \Core\Superclass\Mapper {
    private $_select = 'SELECT *, games.id as id FROM games';
    private $_joins = '
        LEFT JOIN agents victor
            ON games.victor = victor.id
    ';
    
    public function find_by_id($id){
        $sth = $this->pdo->prepare(
            $this->_select . $this->_joins .
            "WHERE games.id = :id"
        );
        $sth->execute(array(
            ':id' => $id
        ));
        return $this->create_object($sth->fetchObject());
    }
    public function create_object($data) {
        return new Game($data);
    }
}

class GameContainer extends \Core\Container {
}
?>
