<?php
class MessageForm extends CFormModel
{
	public $from;
	public $to;
	public $subject;
	public $body;
	public $is_system = 0;

	public function rules()
	{
		return array(
			array('to, subject, from, body', 'required'),
			array('to', 'validateTo'),
			array('to, subject, from, body', 'safe'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'subject'=> Lang::t('messages', 'Subject'),
			'to'=> Lang::t('messages', 'To'),
			'body'=> Lang::t('messages', 'Body'),
		);
	}
	
	public function validate($attributes=null, $clearErrors=true)
	{
	    return parent::validate($attributes, $clearErrors);
	}
	
	public function validateTo(){
		if(isset($this->to)){
			if(!Member::model()->exists('username = :username',array(':username' => $this->to))){
				$this->addError('to', Lang::t('messages', 'This username doesn\'t exist'));
			}
		}
		
	}
	
	public function bindData($data){
	    $this->attributes = $data;
	    $this->body = trim($this->body);
	    $this->subject = substr($this->body, 0, 100);
	}
	
	public function send(){
	    if(!$this->hasErrors()){
	        $to = Member::model()->findByAttributes(array('alias_name'=>$this->to));
	        $from = Member::model()->findByAttributes(array('alias_name'=>$this->from));
            if(Message::write($to, $from, $this->subject, $this->body, true, $this->is_system)){
                return true;               
                $this->sendMailMsg($to);
            } 
	    }
	}
	
	public function sendMailMsg($to){
	    if(!empty($to)){
	        $time = strtotime("-3 hours");
    	    $criteria=new CDbCriteria();
            $criteria->alias = 'm';
            $criteria->addCondition("m.id in (SELECT id FROM (SELECT id FROM `usr_message` WHERE (to_user_id = :to) AND answered = 0 AND `status` != :status3 AND ((to_user_id = :to AND `status` != :status2) OR (`status` = :status)) AND `timestamp` BETWEEN :fromtime AND :totime ORDER BY id ASC LIMIT 30 OFFSET 0) as tbl)");
            $criteria->params = array(':to'=>$to->id, ':status' => Message::STATUS_ENABLE, ':status1' => Message::DELETE_FROM, ':status2' => Message::DELETE_TO , ':status3' => Message::DELETE_ALL, ':fromtime'=>$time, ':totime'=>time());
            $criteria->order='m.timestamp DESC';
            $messages = Message::model()->findAll($criteria);
            
            $to_email = $to->profile->email;        
            $cri = new CDbCriteria();
            $cri->addCondition("`type` = :type AND `to` = :email");
            $cri->params = array(':type'=>SimpleMailer::MAIL_MESSAGE_SENT, ':email'=>$to_email);
            $cri->order = "id DESC";
            $record = SimpleMailerQueue::model()->find($cri);            
            if(((!empty($record) && $record->create_time <= $time ) || empty($record))&& !empty($messages) && !empty($to_email)){            
                $subject = Lang::t('mail','New messages!');
                $body = Yii::app()->controller->renderPartial('//layouts/email/'.Yii::app()->language.'/new-messagesent',array('to'=>$to, 'messages'=>$messages), true);
                SimpleMailer::addQueue(array(
                    'from'=>Yii::app()->params['noreplyEmail'],
                    'to'=>$to_email,
                    'subject'=>$subject,
                    'body'=>$body,
                    'type'=>SimpleMailer::MAIL_MESSAGE_SENT,
                ));
            }
	    }
	}
}
