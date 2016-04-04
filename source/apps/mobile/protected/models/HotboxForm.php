<?php
class HotboxForm extends Hotbox
{
	
	public function rules()
	{
		return array(
			array('type, title, body', 'required'),
			//array('tmp_event_title, tmp_event_start_date, tmp_event_end_date', 'required', 'on' => 'event'),
			//array('tmp_event_end_date', 'validateDateEvent'),
			array('title, slug, description, body, thumbnail_id, meta_description, meta_keywords, tmp_images, tmp_event_title, tmp_event_start_date, tmp_event_end_date, tmp_event_is_always', 'safe'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'type'=>'Type',
			'title'=>'Title',
			'description'=>'Description',
			'body'=>'Body',
			'tmp_images'=>'Upload',
			'tmp_event'=>'Event',
			'tmp_event_title'=>'Event Title',
			'tmp_event_start_date'=>'Event Start Date',
			'tmp_event_end_date'=>'Event End Date',
		);
	}
	
	
	
	public function validateDateEvent(){
		if(strtotime($this->tmp_event_start_date) > strtotime($this->tmp_event_end_date)){
			$this->addError('tmp_event_end_date', 'End day must be greater than start day');
		}
	}
}
