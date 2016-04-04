<?php 
      	$criteria = new CDbCriteria();
		$criteria->condition = "type=2 AND name NOT IN( SELECT child FROM ".ItemChildren::model()->tableName()." WHERE child = name)";
		$criteria->order = "name";
		$authItem = SHtml::listData(AuthItem::model()->findAll($criteria), 'name', 'name');
		
		if(!empty($authItem)){
			$attribute = array();
			foreach ($authItem as $item){
				$return = Helper::recursiveAuthItem(null, $item);
				if(!empty($return)){		
					$attribute = array_merge($attribute, $return);
				}
			}
		}
      	echo SHtml::activeDropDownList(AuthItem::model(),'name[0]',
        $attribute,
        array('size'=>$this->module->listBoxNumberOfLines,'class'=>'dropdown', 'id'=>'cbRoleToRole', 'ajax' => array(
        'type'=>'POST',
        'url'=>array('getRolesToRoles'),
        'update'=>'#rolesToRoles',
        'beforeSend' => 'function(){
                      $("#loadMessRole").addClass("srbacLoading");
                  }',
        'complete' => 'function(){
                      $("#loadMessRole").removeClass("srbacLoading");
                  }'
        ),
        'live'=>false
        )); ?>