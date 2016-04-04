<form method="get" class="frm-quicksearch" id="frm-quicksearch" action="<?php echo Yii::app()->createUrl('//my/quicksearch');?>">
    <div class="slideFalse">
        <input autocomplete="On" id="quicksearch_suggestuser" name="q" type="text" onfocus="if(this.value==this.defaultValue) this.value=''" onblur="if(this.value=='') this.value=this.defaultValue" value="<?php echo !empty($q)    ?   $q:'Search'; ?>" class="quicksearch" />
        <?php 
       			 $params = CParams::load ();
                $this->widget('backend.extensions.select2.ESelect2',array(
                  'selector'=>"#quicksearch_suggestuser",
                  'options'=>array(
                    'allowClear'=>true,
                    'containerCssClass' =>  'quicksearch_suggestuser',
                    'dropdownCssClass'  =>  'result_quicksearch_suggestuser',
                    'closeOnSelect' =>  true,
                    'minimumInputLength' => 2,
                    'multiple'=>true,        
                    'opened'    =>  'js:function() {return false;}',                    
                    'ajax'=>array(
                		'quietMillis'=> 700,
                        'url'=> $params->params->base_url . '/search/SearchUsersSuggest',
                        'dataType'=>'json',
                        'data'=>'js:function(term,page) { return {q: term, page_limit: 3, page: page}; }',
                        'results'=>'js:function(data,page) { return {results: data}; }'
                    ),
                    'formatResult'=>'js:function(item) {
                          if(item.id == 0){
                            return "<div class=\"search-suggestuser-item search-for\">" + "<p><a target=\"_blank\" href=\""+ item.url + "\">" + item.text + "</a></p></div>";
                          }else{
                            return "<div class=\"search-suggestuser-item\"><a target=\"_blank\" href=\""+ item.url + "\"><img class=\"left\" width=\"40px\" height=\"40px\" src=\"" + item.ava + "\" onerror=\"$(this).attr(\'src\',\'/public/images/no-user.jpg\);\"/></a>" + 
                            "<p><a target=\"_blank\" href=\""+ item.url + "\">" + item.text + "</a></p></div>";
                          }
                     }',
					'formatInputTooShort'=>'js:function(input, min) {
                  		var n = min - input.length;
						return tr("Please enter &1 more characters", 2);                  			
                     }',                  		
                  )
                ));
        ?>
    </div>
    <input type="hidden" name="alias" value="<?php echo $userCurrent->getAliasName(); ?>" />
</form>
