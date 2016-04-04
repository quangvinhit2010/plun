<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/resources/js/util/activation.js', CClientScript::POS_BEGIN);
if($user->isActive()){
?>
    <script type="text/javascript">
        jQuery('document').ready(function(){
        	Activation.isActive = true;
        });
    </script>
<?php 
}else{
    $msg = Yii::app()->controller->renderPartial('//Notify/account-re-activation',array('user'=>$user, 'opt'=>1), true);
    $this->widget('frontend.widgets.UserPage.PopupAlert', array('class'=>'activation', 'content'=>$msg));
    
    $msg = Yii::app()->controller->renderPartial('//Notify/account-re-activation',array('user'=>$user, 'opt'=>2), true);
    $this->widget('frontend.widgets.UserPage.PopupAlert', array('class'=>'resend-activation', 'content'=>$msg));
?>
    <script type="text/javascript">
        jQuery('document').ready(function(){
        	Activation.featureMustActive(); 
        });
    </script>
<?php 
}
?>
