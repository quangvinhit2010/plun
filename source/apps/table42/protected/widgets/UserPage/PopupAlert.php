<?php
class PopupAlert extends CWidget {
    public $class = '&nbsp';
    public $content = '&nbsp';
    public $attributtes = array();
    public function run() {
        $attributtes = '';
        if(!empty($this->attributtes)){
            foreach ($this->attributtes as $key=>$value){
                $attributtes .= $key.'="'.$value.'" ';
            }
        }
		$this->render('popup-alert', array(
            'class'=>$this->class,   		       
            'content'=>$this->content,    		       
            'attributtes'=>$attributtes,    		       
		));
    }
    
}