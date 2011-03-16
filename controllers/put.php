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

    /**
     * TODO: restrict agent to logged in user or admin
     */
    public function save_agent() {
        import('core.validation');
        import('trouble.agent');

        $validator = \Core\Validator::validator('\Trouble\Agent');
        $editing = $_POST['id'] > 0 ? True : False;
        
        if($editing) {
            $agent = \Trouble\Agent::container()
                ->get_by_id($_POST['id']);
            $validator->set_id($agent->id);
            $_POST['alias'] = $agent->alias;
            $agent->overwrite($_POST, True);
        } else {
            $agent = \Trouble\Agent::create($_POST, True);
        }
        try {
            $validator->validate($_POST, \Trouble\Agent::validation());
            try {
                \Core\Auth::hash($agent, 'password');
            } catch(\Core\AuthEmptyPasswordError $e) {
                $agent->remove('password');
            }
            // Authorization here. If currently logged in
            // user can update agent of alias x
            \Core\Storage::container()
                ->get_storage('Agent')
                ->save($agent);

            if($editing) {
                $this->_auth->user_data($agent);
                $this->_return_message("Success", "Saved.");            
            } else {
                $this->_return_message("Success", "Created agent. You may now log in.");
            }
        
        } catch(\Core\ValidationError $e) {
            $this->_return_message("Fail",
                "Validation error(s):",
                $e->get_errors());
        }
    }

    public function save_game() {
        import('core.validation');
        import('trouble.game');
        $validator = \Core\Validator::validator('\Trouble\Gane');
        $editing = $_POST['id'] > 0 ? True : False;
        if($editing) {
            $game = \Trouble\Game::container()
                ->get_by_id($_POST['id']);
            $validator->set_id($game->id);
            $game->overwrite($_POST, True);
        } else {
            $game = \Trouble\Game::mapper()
                ->create_object($_POST);
            $game->creator = $this->_auth->user_id();
        }
        try {
            try {
                \Core\Auth::hash($game, 'password');
            } catch(\Core\AuthEmptyPasswordError $e) {
                $game->remove('password');
            }

            \Core\Storage::container()
                ->get_storage('Game')
                ->save($game);
            
            if($editing) {
                $this->_return_message("Success", "Saved.");            
            } else {
                $this->_return_message("Success", "Created game.");
            }

        } catch(\Core\ValidationError $e) {
            $this->_return_message("Fail",
                "Validation error(s):",
                $e->get_errors());
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
        import('trouble.kill');
        import('core.validation');
        try {
            $validator = \Core\Validator::validator('\Trouble\Kill');
            $validator->validate($_POST, \Trouble\Kill::validation());

            $this->_game_action(function($uid) {
                $now = new \DateTime();
                if(empty($_POST['when_happened_date'])) {
                    $date = $now->format('Y-m-d');
                } else {
                    $date = $_POST['when_happened_date'];
                }
                if(empty($_POST['when_happened_time'])) {
                    $time = $now->format('H:i');
                } else {
                    $time = $_POST['when_happened_time'];
                }
                $_POST['when_happened'] = new \DateTime($date . $time);
                $game = \Trouble\Game::container()
                    ->get_by_id($_POST['game_id'])
                    ->kill_agent_target($uid, $_POST);
            }, 'Kill registered.');
        } catch(\Trouble\GameIncorrectPKNError $e) {
            $this->_return_message("Error",
                "Incorrect PKN. If believed to be correct, please contact game administrator.");
        } catch(\Trouble\KillTooEarlyError $e) {
            $this->_return_message("Error",
                "Kill date too early for game.");
        } catch(\Trouble\KillTooLateError $e) {
            $this->_return_message("Error",
                "Kill date too late for game.");
        } catch(\Trouble\KillInFutureError $e) {
            $this->_return_message("Error",
                "Kill date in future. We don't allow time travellers.");
        } catch(\Core\ValidationError $e) {
            $this->_return_message("Error",
                "Validation error(s):",
                $e->get_errors());
        }
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
        } catch(\Trouble\GameNotStartedError $e) {
            $this->_return_message("Error",
                "Game has not started.");
        } catch(\Trouble\GameEndedError $e) {
            $this->_return_message("Error",
                "Game has already ended.");
        }
    }

    protected function _return_message($status, $message, $errors=array()) {
        echo json_encode(array(
            'status'=> $status,
            'message' => $message,
            'errors' => $errors
        ));
    }

}

?>