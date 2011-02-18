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
import('core.auth');

class Index extends \Trouble\StandardPage {
    public function index() {
        $t = $this->_template;
        echo $t->render('main.php');
    }
    public function login() {
        $auth = \Core\Auth::container()
            ->get_auth('Agent', $this->_session, array(
                'user_field' => 'alias'
            ));
        
        try {
            $auth->attempt($_POST['username'], $_POST['password']);        
        } catch(\Core\InvalidUserError $e) {
            echo "invalid user";
        } catch(\Core\IncorrectPasswordError $e) {
            echo "invalid password";
        }

        header("Location: {$_POST['uri']}");
    }

    public function logout() {
        $auth = \Core\Auth::container()
            ->get_auth('Agent', $this->_session, array(
                'user_field' => 'alias'
            ));
        $auth->logout();
        header("Location: {$_POST['uri']}");
    }
}
