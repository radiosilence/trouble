<?php

/*
 * This file is part of trouble.
 *
 * (c) James Cleveland <jamescleveland@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Controllers;

import('core.types');
import('controllers.standard_page');

class Forums extends \Controllers\StandardPage {
    public function index() {
        parent::__construct();
        $t = $this->_template;
        $t->content = "Nothing to see here.";
        echo $t->render('main.php');
    }
}