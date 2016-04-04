<?php

class Photodetail extends CWidget {

    public function run() {
    	
        if (!empty($this->controller->user)) {
            $this->render('photo_detail', array(
				
            ));
        }
    }

}