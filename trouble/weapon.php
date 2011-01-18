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

class Weapon extends \Core\Mapped {

}

class WeaponMapper extends \Core\Mapper {
    public function create_object($data) {
        return Weapon::create($data);
    }
}