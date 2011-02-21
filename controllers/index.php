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
    public function index() {
        $t = $this->_template;
        import('trouble.agent');
        $agents = \Trouble\Agent::mapper()
            ->attach_storage(\Core\Storage::container()
                ->get_storage('Agent')
            )
            ->get_list(array(
                'fields' => array(
                    'alias'
                )
            ));
        $list = "<article><p>Current Agents:</p><ul>";
        foreach($agents as $agent) {
            $list .= "<li>{$agent->alias}</li>";
        }
        $list .= "</ul></article>";
        $t->content = $list;
        echo $t->render('main.php');
    }
    public function login() {
        $auth = \Core\Auth::container()
            ->get_auth('Agent', $this->_session, array(
                'user_field' => 'alias'
            ));
        
        try {
            $auth->attempt($_POST['username'], $_POST['password']);        
            header("Location: {$_POST['uri']}");
        } catch(\Core\InvalidUserError $e) {
            $this->_login_fail();
        } catch(\Core\IncorrectPasswordError $e) {
            $this->_login_fail();
        }
    }

    protected function _login_fail() {
        $this->_template->msg_login = "Invalid user/password.";
        $this->_t_login_box();
        $this->index();
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
