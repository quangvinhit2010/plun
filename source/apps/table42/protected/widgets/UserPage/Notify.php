<?php

class Notify extends CWidget {
    public $view;
    public function run() {
        if (!Yii::app()->user->isGuest && !empty($this->view)) {
            $user = Yii::app()->user->data();
            $data = array();
            switch ($this->view){
                case 'account-activation';
                    $data['user'] = $user;
                    break;
            }
            $this->render($this->view, $data);
        }
    }

}