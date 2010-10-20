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

import('core.superclass.mapping');

class Agent extends \Core\Superclass\Mapped {}

class AgentMapper extends \Core\Superclass\Mapper {
    private $_select = 'SELECT * FROM agents';
    private $_joins = null;
    
    public function create_object($data) {
        return new Agent($data);
    }
    
}

?>