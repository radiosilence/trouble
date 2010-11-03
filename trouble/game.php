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
import('core.mapping.pdo');
import('core.containment');

import('trouble.kill');

class Game extends \Core\Mapped {

    public function __construct() {
        $this->kills = \Core\Arr::create();
    }

    public function load_kills() {
        $this->kills->extend($this->mappers['kill']
                ->find_by_game($this));
    }
}

class GameMapper extends \Core\Mapping\PDOMapper {
    protected $_select = '
        SELECT games.*,
            games.id as id,
            victor.alias as victor_alias
        FROM games
    ';
    protected $_joins = '
        LEFT JOIN agents victor
            ON games.victor = victor.id
    ';

    public function get_list($order='games.start_date desc', $limit=20, $filter=null) {
        $sth = $this->pdo->prepare(sprintf(
            "%s\n%s\n%s\nORDER BY %s\nLIMIT %s",
            $this->_select,
            $this->_joins,
            $filter,
            $order,
            (int)$limit
        ));
        $sth->execute();
        $games = \Core\Arr::create();
        while($data = $sth->fetch(\PDO::FETCH_ASSOC)) {
            $games->append($this->create_object($data));
        }
        return $games;
    }

    public function find_by_id($id){
        $sth = $this->pdo->prepare(
            $this->_select . $this->_joins .
            "WHERE games.id = :id"
        );
        $sth->execute(array(
            ':id' => $id
        ));
        return $this->create_object($sth->fetch(\PDO::FETCH_ASSOC));
    }

    public function create_object($data) {
        $data['victor'] = Agent::mapper()->create_object(array(
            'id' => $data['victor'],
            'alias' => $data['victor_alias']
        ));
        unset($data['victor_alias']);
        $game = Game::create($data);
        $game->attach_mapper('kill', Kill::mapper()
            ->attach_pdo($this->pdo));
        return $game;
    }
}

class GameContainer extends \Core\Container {
}
?>
