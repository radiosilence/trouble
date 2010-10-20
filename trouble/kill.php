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

import('core.superclass.pdo');

class Kill extends \Core\Superclass\PDOStored {

    public static $table = 'kills';
    
	/**
	 * Registers kill of $assassin's target.
	 * Sets target as dead, makes a killboard stub.
	 */
	public function register_kill() {
		# 1. Initialise target
		# 2. Set target dead
		# 3. Initialise assassin
		# 4. Increase kill count
		# 5. Assign new target
		# 6. E-mail killer new target?
	}
	public function default_form() {
		return array(
			"Kill" => array(
				"weapon",
				"description",
				"assassin",
				"target",
				"timestamp"
			),
			"Contest" => array(
				"contested",
				"contest"
			)
		);
	}
}
?>
