<?php

namespace Controllers;

import('controllers.standard_page');
class Put extends \Controllers\StandardPage {
    public function __construct($args) {
        parent::__construct($args);
        if($this->_session->get_tok() != $_POST['tok']) {
            die(json_encode(array('401' => "Not Authorized")));
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
        parent::__construct();
        $uid = $this->_user['id'];
        echo json_encode(array('status'=>"JOINING USER $uid TO GAME {$_POST[id]}"));
    }
}

?>