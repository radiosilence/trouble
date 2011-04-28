<?php

namespace Controllers;

import('controllers.standard_page');
import('core.exceptions');

class Put extends \Controllers\StandardPage {
    protected $_async;

    public function __construct($args) {
        parent::__construct($args);
        $this->_throttle();
        $tok = ($_POST['_tok'] ? $_POST['_tok'] : $_GET['_tok']);
        if($this->_session->get_tok() != $tok) {
            throw new \Core\HTTPError(401, $this->_args['method']);
        }
        $this->_async = True;
    }
    public function index() {}
    public function save_image() {
        import('3rdparty.qquploader');
        if($_GET['type'] == 'avatar') {
            $type = 'avatar';
        } else if($_GET['type'] == 'game') {
            $type = 'game';
        } else if($_GET['type'] == 'photo') {
            $type = 'agent';
        } else {
            exit("Bad type");
        }

        // list of valid extensions, ex. array("jpeg", "xml", "bmp")
        $allowedExtensions = array('jpeg', 'png', 'jpg');
        // max file size in bytes
        $sizeLimit = 1 * 1024 * 1024;
        $uploader = new \qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload("img/{$type}/", False, True);
        $file = "{$result['filename']}.{$result['ext']}";
        if($type == 'avatar') {
            $this->thumb_image($file, 'avatar', 80);
        } else if($type == 'game') {
            $this->thumb_image($file, 'game', 120);
        }
        // to pass data through iframe you will need to encode all html tags
        echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
    }

    protected function thumb_image($file, $type, $x, $y=False) {
        if(!$y) {
            $y = $x;
        }
        $filename = realpath(SITE_PATH
            ."/_wwwroot/img/{$type}/{$file}");

        $out = array();
        exec("convert {$filename} -resize {$x}x{$y}^ -gravity center -extent {$x}x{$y} $filename", $out);
        return $out;
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
                ->load_players();
            
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

    public function redeem_credit() {
        import('trouble.voucher');
        try {
            $this->_game_action(function($agent_id) {
                $game = \Trouble\Game::container()
                    ->get_by_id($_POST['game_id'])
                    ->load_players();
                
                $p = $game->all_players->filter($agent_id, 'agent')->{0};
                $v = \Trouble\Voucher::container()
                    ->get_valid($_POST['voucher'], False, $game)
                    ->spend();
                $p->credits = $p->credits + $v->credit_value();
                $p->save();

            }, 'Voucher redeemed.');
        } catch(\Trouble\VoucherError $e) {
            echo $this->_return_message('Fail',
                'Invalid voucher.');
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
                if(empty($_POST['password'])) {
                    throw new \Core\ValidationError(array(
                        'Password must be set on creation.'));
                }
                $agent = \Trouble\Agent::create($_POST, True);
            }
            $validator->validate($_POST);
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

        $validator = \Core\Validator::validator('\Trouble\Game');
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
            $validator->validate($_POST);

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
        import('core.hasher');
        import('trouble.voucher');
        try {
            $this->_game_action(function($agent_id) {
                $game = \Trouble\Game::container()
                    ->get_by_id($_POST['game_id'])
                    ->test_entry($_POST);
                $game->add_agent($agent_id);                            
            }, 'Joined game.');
        } catch(\Trouble\VoucherError $e) {
            echo $this->_return_message('Fail',
                'There was a problem with your voucher.');
        } catch(\Core\HashMismatch $e) {
            echo $this->_return_message('Fail',
                'Incorrect password.');
        }
    }

    protected function _test_can_join($game, $data) {
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