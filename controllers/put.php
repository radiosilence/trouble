<?php

namespace Controllers;

import('controllers.standard_page');
import('core.exceptions');

class Put extends \Controllers\StandardPage {
    public function __construct($args) {
        parent::__construct($args);
        $tok = ($_POST['tok'] ? $_POST['tok'] : $_GET['tok']);
        if($this->_session->get_tok() != $tok) {
            throw new \Core\HTTPError(401, $this->_args['method']);
        }
    }
    public function index() {}
    public function save_agent_image() {
        import('3rdparty.qquploader');
        // list of valid extensions, ex. array("jpeg", "xml", "bmp")
        $allowedExtensions = array('jpeg', 'png', 'jpg');
        // max file size in bytes
        $sizeLimit = 0.5 * 1024 * 1024;

        $uploader = new \qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload('img/agent/', False, True);
        // to pass data through iframe you will need to encode all html tags
        echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
    }

    public function login() {
        if(!\Core\Utils\Env::using_ssl()) {
            //throw new \Core\Error("This page must use SSL!");
        }
        
        if(!isset($_POST['username']) || !isset($_POST['password'])) {
            $this->_return_message("Fail", 
                "Please enter alias and password.");
        }
        try {
            $this->_auth->attempt($_POST['username'], $_POST['password']);        
            $this->_return_message("Success", "Logged in.");
        } catch(\Core\InvalidUserError $e) {
            $this->_return_message("Fail",
                "Invalid alias.");
        } catch(\Core\IncorrectPasswordError $e) {
            $this->_return_message("Fail",
                "Invalid password.");
        }
    }

    public function logout() {
        $this->_auth->logout();
        $this->_return_message("Success", "Logged out.");
    }

    public function join_game() {
        $this->_game_action(function($uid) {
            $game = \Trouble\Game::container()
                ->get_by_id($_POST['id'])
                ->add_agent($uid);        
        }, 'Joined game.');
    }

    public function leave_game() {
        $this->_game_action(function($uid) {
            $game = \Trouble\Game::container()
                ->get_by_id($_POST['id'])
                ->remove_agent($uid);
        }, 'Left game.');
    }

    public function register_kill() {
        $this->_game_action(function($uid) {
            $_POST['when_happened'] = new \DateTime($_POST['when_happened_date'] . $_POST['when_happened_time']);
            $game = \Trouble\Game::container()
                ->get_by_id($_POST['game_id'])
                ->kill_agent_target($uid, $_POST);
        }, 'Kill registered.');     
    }

    protected function _game_action($callback, $success=False) {
        import('trouble.game');
        try {
            $uid = $this->_auth->user_id();
            $callback($uid);
            if($success) {
                $this->_return_message("Success",
                    $success);
            } else {
                $this->_return_message("Success",
                    "Success.");
            }
        } catch(\Core\AuthNotLoggedInError $e) {
            $this->_return_message("Error",
                "Not logged in.");
        } catch(\Trouble\GameAgentNotInGameError $e) {
            $this->_return_message("Error",
                "You are not in this game.");
        } catch(\Trouble\GameCannotRejoinError $e) {
            $this->_return_message("Error",
                "You cannot rejoin a game in this state.");
        } catch(\Trouble\GameAlreadyHasAgentError $e) {
            $this->_return_message("Error",
                "You are already in this game.");
        } catch(\Trouble\GameNotJoinableError $e) {
            $this->_return_message("Error",
                "Game is not joinable.");
        }
    }

    protected function _return_message($status, $message) {
        echo json_encode(array(
            'status'=> $status,
            'message' => $message
        ));
    }

}

?>