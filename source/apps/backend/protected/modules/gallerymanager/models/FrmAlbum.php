<?php
/**
 * FrmAlbum class.
 * FrmAlbum is the data structure for keeping
 */
class FrmAlbum extends CFormModel
{
	public $name;
	public $description;
	public $versions_data;
	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('name, description', 'required'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'name'=>'Name',
			'description'=>'Description',
		);
	}
	
	public function save()
	{
		if(!$this->hasErrors()){
			$gallery = new Gallery();
			$gallery->attributes = $this->attributes;
			$gallery->versions = array(
					'small' => array(
							'resize' => array(200, null),
					),
					'medium' => array(
							'resize' => array(800, null),
					)
			);
			$gallery->validate();
			if(!$gallery->hasErrors()){
				$gallery->save();
			}
		}
	}
}
