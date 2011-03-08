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
import('core.storage');

class Agent extends \Core\Mapped {
    protected $_fields = array("fullname", "alias",
        "email", "phone", "address", "course",
        "societies", "clubs", "timetable", "imagefile",
        "password");

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

class AgentMapper extends \Core\Mapper {
    public function create_object($data) {
        return Agent::create($data);
    }
}

class AgentContainer extends \Core\MappedContainer {
    public function get_by_alias($alias) {
        return $this->get_by_field('alias', $alias);
    }
}
?>
