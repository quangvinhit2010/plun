<?php
class Background extends CActiveRecord {
	
	public $id;
	public $file_name;
	public $size;
	
	public function tableName() {
		return 'sys_background';
	}
	public function getListImage() {
		return array(
			array('file_name' => 'newBG', 'size' => '1851x1159', 'label' => 'Desktop (1851x1159) - Upload File size(1851x1159)'),
			array('file_name' => 'size_240', 'size' => '240x300', 'label' => 'Small mobile (240x300) - Upload File size(240x300)'),
			array('file_name' => 'Size640x1136', 'size' => '640x1136', 'label' => 'Iphone 5 portrait (320x568) + Nokia 520 portrait (320x480) - Upload File size(640x1136)'),
			array('file_name' => 'Size720x1196', 'size' => '720x1196', 'label' => 'Sony portrait (360x598) - Upload File size(720x1196)'),
			array('file_name' => 'Size768x1024', 'size' => '768x1024', 'label' => 'Android Nexus 4 portrait (384x512) - Upload File size(768x1024)'),
			array('file_name' => 'Size960x640', 'size' => '960x640', 'label' => 'Iphone 4, 4s lanscape (480x320) - Upload File size(960x640)'),
			array('file_name' => 'Size1068x640', 'size' => '1068x640', 'label' => 'HTC lanscape (534x320) - Upload File size(1068x640)'),
			array('file_name' => 'Size1280x720', 'size' => '1280x720', 'label' => 'Samsung Note 2 lanscape (640x360) - Upload File size(1280x720)'),
			array('file_name' => 'Size1536x2048', 'size' => '1536x2048', 'label' => 'Ipad portrait (768x1024) + Kindle 600 portrait (600x820) - Upload File size(1536x2048)'),
			array('file_name' => 'Size2048x1536', 'size' => '2048x1536', 'label' => 'Ipad lanscape (1024x768) - Upload File size(2048x1536)'),
		);
	}
}