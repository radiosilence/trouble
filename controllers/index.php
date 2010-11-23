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

import('trouble.pages');
import('core.types');

class Index extends \Trouble\StandardPage {
    public function index() {
        $t = new \Core\Template();
        echo $t->render('main.php');
    }

}
