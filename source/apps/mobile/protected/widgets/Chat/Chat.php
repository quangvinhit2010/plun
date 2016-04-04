<?php
class Chat extends CWidget {
    public function run() {
    	if(Yii::app()->user->id){
    		$this->render('chat', array());
    	}
    }
    
}