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

class Agent extends \Core\Mapped {}

class AgentMapper extends \Core\Mapping\PDOMapper {
    protected $_select = 'SELECT * FROM agents';
    
    public function create_object($data) {
        return Agent::create($data);
    }
    
    public function find_by_alias($alias) {
        $sth = $this->pdo->prepare(sprintf(
                "%s\nWHERE alias = :alias",
                $this->_select
        ));
        $sth->execute(array(
            ':alias' => $alias
        ));
        return $this->create_object($sth->fetch(\PDO::FETCH_ASSOC));
    }
}

?>
