<?php
class CreateTmpData {
	public function InsertData(){
		$userDataSearch = new UserDataSearch();
		$userDataSearch->user_id = '';
		$userDataSearch->save();
		
		
		UserDataSearch::model()->findAll();
	}
}