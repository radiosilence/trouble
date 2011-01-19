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
    public $kills;

    public function __construct($array=False) {
        parent::__construct($array);
    }

    public function load_kills($args=False) {
        $this->kills = \Core\Li::create($this->mappers['kill']
                ->find_by_game($this, $args)
        );
    }
}

class GameMapper extends \Core\Mapper {
    private $_default_order = array("start_date", "desc");

    public function get_list(\Core\Dict $parameters) {
    //echo "<params>"; var_dump($parameters);  echo "</params>";   
        if(!$order) {
            $order = $_default_order;
        }
        $parameters->joins = new \Core\Li(
            new \Core\Join("victor", "Agent", new \Core\Li('alias'))
        );
        $results = $this->_storage->fetch($parameters);
        $games = new \Core\Li();
        foreach($results as $result) {
            $games->append($this->create_object($result));            
        }
        return $games;
    }

    public function find_by_id($id){
        if(!is_int($id)) {
            return False;
        }
        $result = $this->_storage->fetch(new \Core\Dict(array(
                "filters" => new \Core\Li(
                    new \Core\Filter("id", $id)
                )
        )));
        return $this->create_object($result[0]);
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
        $game->attach_mapper('kill',
            Kill::mapper()
                ->attach_storage($this->_storage)
        );
        return $game;
    }
}

class GameContainer extends \Core\Container {
}
?>
