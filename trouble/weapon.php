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

class Weapon extends \Core\Mapped {

}

class WeaponMapper extends \Core\Mapping\PDOMapper {
    protected $_select = 'SELECT * FROM weapons';
    protected $_joins = null;
    
    public function create_object($data) {
        return Weapon::create($data);
    }
}
