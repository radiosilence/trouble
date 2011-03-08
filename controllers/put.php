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

    public function join_game() {
        import('trouble.game');
        $uid = $this->_user['id'];
        $game = \Trouble\Game::container()
            ->get_by_id($_POST['id'])
            ->add_agent($uid);
        
        try {
            $game->add_agent($uid);
            echo json_encode(array(
                'status'=> "OK",
                'message' => "Successfully joined game."
            ));
        } catch(\Trouble\GameAlreadyHasAgentError $e) {
            echo json_encode(array(
                'status'=> "Fail",
                'message' => "You are already in this game."
            ));
        } catch(\Trouble\GameNotJoinableError $e) {
            echo json_encode(array(
                'status'=> "Fail",
                'message' => "Game not joinable."
            ));
        }
    }
}

?>