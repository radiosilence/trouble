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

class Agent extends \Core\Mapped {
    public static function validation() {
       return array(
            'fullname' => 'default',
            'alias' => array (
                array(
                    'type' => 'unique',
                    'mapper' => 'agent'
                ),
                'default'
            ),
            'email' => array(
                array(
                    'type' => 'unique',
                    'mapper' => 'agent'
                ),
                'email'
            )
        );
    }
}

class AgentMapper extends \Core\Mapping\PDOMapper {
    protected $_select = 'SELECT * FROM agents';
    protected $_insert = "INSERT INTO agents";
    protected $_update = "UPDATE agents SET ";
    protected $_delete = "DELETE FROM agents ";
    protected $_order = "ORDER BY agents.id DESC";
    protected $_fields = array("id", "fullname", "alias", "email",
        "phone", "address", "course", "societies", "clubs", "timetable",
        "imagefile");
    
    public function create_object($data) {
        return Agent::create($data);
    }
}

?>
