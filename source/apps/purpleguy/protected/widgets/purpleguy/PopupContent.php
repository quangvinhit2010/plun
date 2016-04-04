<?php
class PopupContent extends CWidget {
    public $view = '';
    public $data = array();
    public function run() {
        if(!empty($this->view)){
            switch ($this->view){
                case 'thamgia':
                    break;
            }
    		$this->render($this->view, $this->data);
        }
    }
    
}