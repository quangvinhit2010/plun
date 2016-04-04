<?php
class HotboxForm extends Hotbox
{
	
	public function rules()
	{
		return array(
			array('type, title, body', 'required'),
			array('start, end, country_id', 'required', 'on' => 'event'),
			//array('tmp_event_title, tmp_event_start_date, tmp_event_end_date', 'required', 'on' => 'event'),
			//array('tmp_event_end_date', 'validateDateEvent'),
			array('end', 'validateDateEvent'),
			array('title, slug, description, body, thumbnail_id, meta_description, meta_keywords, tmp_images, tmp_event_title, tmp_event_start_date, tmp_event_end_date, tmp_event_is_always', 'safe'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'type'=>'Type',
			'title'=>Lang::t('hotbox', 'Title'),
			'description'=>'Description',
			'body'=>Lang::t('hotbox', 'Body'),
			'tmp_images'=>'Upload',
			'tmp_event'=>'Event',
			'tmp_event_title'=>'Event Title',
			'tmp_event_start_date'=>'Event Start Date',
			'tmp_event_end_date'=>'Event End Date',
		);
	}
	
	public function validateDateEvent(){
		if(strtotime($this->start) > strtotime($this->end)){
			$this->addError('end', Lang::t('hotbox', 'End day must be greater than start day'));
		}
	}
}
