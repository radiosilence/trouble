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
import('core.utils.env');

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
            $list .= "<li><a href=\"agent/{$agent->alias}/edit\">{$agent->alias}</a></li>";
        }
        $list .= "</ul></article>";
        $t->content = $list;
        $t->title = "Assassins Game Management";
        echo $t->render('main.php');
    }
    public function login() {
        if(!\Core\Utils\Env::using_ssl()) {
            //throw new \Core\Error("This page must use SSL!");
        }
        $auth = \Core\Auth::container()
            ->get_auth('Agent', $this->_session, array(
                'user_field' => 'alias'
            ));
        if(!isset($_POST['username']) || !isset($_POST['password'])) {
            header("Location: /");
        }
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
