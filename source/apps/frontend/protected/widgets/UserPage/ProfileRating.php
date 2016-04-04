<?php
class ProfileRating extends CWidget {
    public $view;
    public function run() {
    	$param = CParams::load();
    	if(empty($param->params->rate->enable)){
    		return false;
    	}
        if (!Yii::app()->user->isGuest && !empty($this->view)) {
            $data = array();
            switch ($this->view){
                case 'profile-rating';
                    break;
            }
            $this->render($this->view, $data);
        }
    }

}