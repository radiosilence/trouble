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

import('controllers.standard_page');
import('core.types');
import('core.auth');

class Index extends \Controllers\StandardPage {
    public function index() {}
    public function login() {
        $this->_throttle();
        try {
            $this->_auth->user_id();
            header('Location: /');
        } catch(\Core\AuthNotLoggedInError $e) {
            $t = $this->_template;
            $t->user_field = "User";
            $t->login_action = '/login';
            $t->set_file('login_page.php');
                
            if(isset($_POST['username'])) {
                try {
                    $this->_auth->attempt($_POST['username'], $_POST['password']); 
                    $t->content = $this->_return_message("Success",
                        "Logged in.");       
                    header('Location: /');
                } catch(\Core\AuthAttemptError $e) {
                    $t->content = $this->_return_message("Fail",
                        "Invalid username or password.");
                }            
            } else {
                $t->content = $t->render();
            }
            $t->_disable_user_box = True;
            echo $t->render('main.php');
        }
    }


    public function logout() {
        $t = $this->_template;
        $t->set_file('message.php');
        
        $this->_auth->logout();

        $t->content = $this->_return_message("Success",
            "Logged out.");
        $t->_disable_user_box = True;
        header('Location: /');
        echo $t->render('main.php');
    }
}
