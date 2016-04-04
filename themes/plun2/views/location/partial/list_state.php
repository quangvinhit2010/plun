<?php
	if(sizeof($list_state) > 0): 
	$first_state	=	current($list_state);	
	
	$html_option		=	false;
	$html_liststate		=	'';
	
	$top_state			=	LocationState::model()->getTopStateByCountry($first_state['country_id']);
	if(sizeof($top_state)){
		$top_state 			= 	CHtml::listData($top_state, 'id', 'name');
		$html_liststate		=	CHtml::listOptions(0, array('------------' => $top_state), $html_option);
	}
	
	$list_state			=	CHtml::listData($list_state, 'id', 'name');
	$html_liststate		=	$html_liststate	.	CHtml::listOptions(0, array('------------' => $list_state), $html_option);
	
	$html_liststate		=	CHtml::listOptions(0, array(Lang::t('search', '--Any--')), $html_option)	.	$html_liststate;
	echo $html_liststate;
endif;