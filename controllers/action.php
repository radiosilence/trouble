<?php

namespace Controllers;

import('trouble.pages');
class action extends \Trouble\StandardPage {
    public function __construct($args) {
        
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
}

?>