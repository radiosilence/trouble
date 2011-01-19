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

import('core.mapping');
import('core.containment');

import('trouble.kill');
import('trouble.agent');

class Game extends \Core\Mapped {

    public function __construct() {
        $this->kills = \Core\CoreList::create();
    }

    public function load_kills($args=False) {
    echo "Loading kills";
        var_dump($this->mappers);
        $this->kills->extend($this->mappers['kill']
                ->find_by_game($this, $args));
    }
}

class GameMapper extends \Core\Mapper {
    private $_default_order = array("start_date", "desc");

    public function get_list(\Core\CoreDict $parameters) {
    echo "<params>"; var_dump($parameters);  echo "</params>";   
        if(!$order) {
            $order = $_default_order;
        }
        $parameters->joins = new \Core\CoreList(
            new \Core\Join("victor", "Agent")
        );
        $results = $this->_storage->fetch_many($parameters);
        $games = \Core\CoreList::create();
        foreach($results as $result) {
            $games->append($this->create_object($result));
        }
        return $games;
    }

    public function find_by_id($id){
        return $this->_storage->fetch($id);
    }

    public function create_object($data) {
        $data['victor'] = Agent::mapper()->create_object(array(
            'id' => $data['victor'],
            'alias' => $data['victor_alias']
        ));
        foreach(array('invite_only', 'local_currency', 'fake_names') as $v) {
            $data[$v] = ($v == 1 ? True : False);
        }
        unset($data['victor_alias']);
        $game = Game::create($data);
        $game->attach_mapper('kill', Kill::mapper()
            ->attach_storage($this->_storage));
        return $game;
    }
}

class GameContainer extends \Core\Container {
}
?>
