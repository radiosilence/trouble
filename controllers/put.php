<?php

namespace Controllers;

import('controllers.standard_page');
import('core.exceptions');

class Put extends \Controllers\StandardPage {
    protected $_async;

    public function __construct($args) {
        parent::__construct($args);
        $tok = ($_POST['_tok'] ? $_POST['_tok'] : $_GET['_tok']);
        if($this->_session->get_tok() != $tok) {
            throw new \Core\HTTPError(401, $this->_args['method']);
        }
        $this->_async = True;
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
            echo $this->_return_message("Fail", 
                "Please enter alias and password.");
        }
        try {
            $this->_auth->attempt($_POST['username'], $_POST['password']);
            echo $this->_return_message("Success", "Logged in.");
        } catch(\Core\AuthAttemptError $e) {
            echo $this->_return_message("Fail",
                "Invalid alias or password.");
        }
    }

    public function buy_intel() {
        import('trouble.intel');
        import('trouble.player');
        import('core.validation');
        $validator = \Core\Validator::validator('\Trouble\OwnedIntel');
        try{
            $validator->validate($_POST);
            $intel = \Trouble\Intel::container()
                ->get_by_id($_POST['intel']);

            $game = \Trouble\Game::container()
                ->get_by_id($_POST['game_id'])
                ->get_players();
            
            $subject = \Trouble\Agent::container()
                ->get_by_id($_POST['subject']);

            $player = $game->all_players->filter($this->_user['id'], 'agent')->{0};

            $existing_intels = \Trouble\OwnedIntel::container()
                ->get_owned_intel($subject, $player);
            
            if($intel->cost > $player->credits) {
                throw new \Trouble\BuyIntelError('You cannot afford this intel.');
            }

            if(!$game->all_players->filter($subject['id'], 'agent')) {
                throw new \Trouble\BuyIntelError('Agent is not in this game.');
            }
            
            if($existing_intels->filter($intel['id'], 'intel', 'id')) {
                throw new \Trouble\BuyIntelError('You already have that piece of intel.');
            }

            $owned = \Trouble\OwnedIntel::create(array(
                'subject' => $subject,
                'player' => $player,
                'game' => $game,
                'intel' => $intel
            ));

            $owned->save();
            $player->credits = $player->credits - $intel->cost;
            $player->save();
            $this->_return_message("Success",
                "Intel has been bought.");

        } catch(\Core\ValidationError $e) {
            echo $this->_return_message("Fail",
                "Validation error(s):",
                $e->get_errors());
        } catch(\Trouble\BuyIntelError $e) {
            echo $this->_return_message("Fail",
                $e->getMessage());
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
        
        try {
            if($editing) {
                if($_POST['id'] != $this->_auth->user_id()) {
                    $this->_auth->check_admin('agent', $_POST['id']);
                }
                $agent = \Trouble\Agent::container()
                    ->get_by_id($_POST['id']);
                $validator->set_id($agent->id);
                $_POST['alias'] = $agent->alias;
                $agent->overwrite($_POST, True);
            } else {
                $agent = \Trouble\Agent::create($_POST, True);
            }
            $validator->validate($_POST, \Trouble\Agent::validation());
            try {
                \Core\Auth::hash($agent, 'password');
            } catch(\Core\AuthEmptyPasswordError $e) {
                $agent->remove('password');
            }
            \Core\Storage::container()
                ->get_storage('Agent')
                ->save($agent);

            if($editing) {
                $this->_auth->user_data($agent);
                echo $this->_return_message("Success", "Saved.");            
            } else {
                echo $this->_return_message("Success", "Created agent. You may now log in.");
            }
        
        } catch(\Core\ValidationError $e) {
            echo $this->_return_message("Fail",
                "Validation error(s):",
                $e->get_errors());
        } catch(\Core\AuthNotLoggedInError $e) {
            $this->_not_logged_in();
        } catch(\Core\AuthDeniedError $e) {
            $this->_access_denied();
        } catch(\Exception $e) {
            $this->_unhandled_exception($e);
        }
    }


    public function save_game() {
        import('core.validation');
        import('trouble.game');

        $validator = \Core\Validator::validator('\Trouble\Gane');
        $editing = $_POST['id'] > 0 ? True : False;
            
        try {
            if($editing) {
                $this->_auth->check_admin('game', $_POST['id']);
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
                \Core\Auth::hash($game, 'password');
            } catch(\Core\AuthEmptyPasswordError $e) {
                $game->remove('password');
            }

            \Core\Storage::container()
                ->get_storage('Game')
                ->save($game);
            
            if(!$editing) {
                $this->_auth->add_admin('game', $game->id, $game->creator);
            }

            if($editing) {
                echo $this->_return_message("Success", "Saved.");            
            } else {
                echo $this->_return_message("Success", "Created game.");
            }

        } catch(\Core\ValidationError $e) {
            echo $this->_return_message("Fail",
                "Validation error(s):",
                $e->get_errors());
        } catch(\Core\AuthNotLoggedInError $e) {
            $this->_not_logged_in();
        } catch(\Core\AuthDeniedError $e) {
            $this->_access_denied();
        } catch(\Exception $e) {
            $this->_unhandled_exception($e);
        }
    }

    public function logout() {
        $this->_auth->logout();
        echo $this->_return_message("Success", "Logged out.");
    }

    public function join_game() {
        try {
            $this->_game_action(function($agent_id) {
            $game = \Trouble\Game::container()
                ->get_by_id($_POST['id'])
                ->add_agent($agent_id);        
            }, 'Joined game.');
        } catch(\Exception $e) {
            $this->_unhandled_exception($e);
        }
    }

    public function leave_game() {
        try {
            $this->_game_action(function($agent_id) {
                $game = \Trouble\Game::container()
                    ->get_by_id($_POST['id'])
                    ->remove_agent($agent_id);
            }, 'Left game.');
        } catch(\Exception $e) {
            $this->_unhandled_exception($e);
        }
    }

    public function register_kill() {
        import('trouble.kill');
        import('core.validation');
        try {
            $validator = \Core\Validator::validator('\Trouble\Kill');
            $validator->validate($_POST, \Trouble\Kill::validation());
            $this->_game_action(function($agent_id) {
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
                    ->kill_agent_target($agent_id, $_POST);
            }, 'Kill registered.');
        } catch(\Trouble\GameIncorrectPKNError $e) {
            echo $this->_return_message("Error",
                "Incorrect PKN. If believed to be correct, please contact game administrator.");
        } catch(\Trouble\KillTooEarlyError $e) {
            echo $this->_return_message("Error",
                "Kill date too early for game.");
        } catch(\Trouble\KillTooLateError $e) {
            echo $this->_return_message("Error",
                "Kill date too late for game.");
        } catch(\Trouble\KillInFutureError $e) {
            echo $this->_return_message("Error",
                "Kill date in future. We don't allow time travellers.");
        } catch(\Core\ValidationError $e) {
            echo $this->_return_message("Error",
                "Validation error(s):",
                $e->get_errors());
        } catch(\Exception $e) {
            $this->_unhandled_exception($e);
        }
    }

    protected function _game_action($callback, $success=False, $agent_id=False) {
        import('trouble.game');

        try {
            if(!$agent_id) {
                $agent_id = $this->_auth->user_id();
            }
            $callback($agent_id);
            if($success) {
                echo $this->_return_message("Success",
                    $success);
            } else {
                echo $this->_return_message("Success",
                    "Success.");
            }
        } catch(\Core\AuthNotLoggedInError $e) {
            $this->_not_logged_in();
        } catch(\Core\AuthDeniedError $e) {
            $this->_access_denied();
        } catch(\Trouble\GameAgentNotInGameError $e) {
            echo $this->_return_message("Error",
                "You are not in this game.");
        } catch(\Trouble\GameCannotRejoinError $e) {
            echo $this->_return_message("Error",
                "You cannot rejoin a game in this state.");
        } catch(\Trouble\GameAlreadyHasAgentError $e) {
            echo $this->_return_message("Error",
                "You are already in this game.");
        } catch(\Trouble\GameNotJoinableError $e) {
            echo $this->_return_message("Error",
                "Game is not joinable.");
        } catch(\Trouble\GameNotStartedError $e) {
            echo $this->_return_message("Error",
                "Game has not started.");
        } catch(\Trouble\GameEndedError $e) {
            echo $this->_return_message("Error",
                "Game has already ended.");
        }
    }
    
    protected function _access_denied() {
        echo $this->_return_message("Error",
                "You are not authorised to do this.");
    }

    protected function _not_logged_in() {
        echo $this->_return_message("Error",
                "Not logged in.");
    }

}

?>