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
    public $players;

    public function __construct($array=False) {
        parent::__construct($array);
    }

    public function load_kills($args=False) {
        $this->kills = $this->_mappers['Kill']
                ->find_by_game($this, $args);
    }

    public function get_players() {
        $x = $this->_mappers['Agent']
            ->get_list(array(
                'in' => new \Core\In('Games', $this->id)
            ));
    }
}

class GameMapper extends \Core\Mapper {
    private $_default_order = array("start_date", "desc");

    public function get_list($parameters=False) {
        if(!$order) {
            $order = $_default_order;
        }
        $parameters['joins'][] = new \Core\Join("victor", "Agent", new \Core\Li('alias'));
        $results = $this->_storage->fetch($parameters);
        $games = new \Core\Li();
        foreach($results as $result) {
            $games->append($this->create_object($result));            
        }
        return $games;
    }


    public function create_object($data) {
        $data['victor'] = Agent::mapper()->create_object(array(
            'id' => $data['victor'],
            'alias' => $data['victor_alias']
        ));
        unset($data['victor_alias']);
        $game = Game::create($data);
        $game->attach_mapper('Kill',
            Kill::mapper()
                ->attach_storage($this->_storage)
        );
        $game->attach_mapper('Agent',
            Agent::mapper()
                ->attach_storage(
                \Core\Storage::container()
                    ->get_storage('Agent')
                )
            );

        //$game->get_players();
        return $game;
    }
}

class GameContainer extends \Core\Container {
}
?>
