<?php

namespace Controllers;

import('controllers.standard_page');
import('trouble.weapon');
class Weapon extends \Controllers\StandardPage {
    public function index() {
        $t = $this->_template;
        $t->weapon = \Trouble\Weapon::container()
            ->get_by_id($this->_args['weapon_id']);
        $t->content = $t->render('weapon.php');
        echo $t->render('main.php');
    }
}