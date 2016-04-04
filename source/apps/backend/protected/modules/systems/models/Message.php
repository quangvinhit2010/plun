<?
class Message extends YumMessage
{
    const STATUS_ENABLE = 1;
    const DELETE_FROM = -1;
    const DELETE_TO = -2;
    const DELETE_ALL = -3;
    const READ = 1;
    const UNREAD_FROM = 0;
    const UNREAD_TO = 2;
    
    public $to_user_reply;
    
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
    
    
    public function relations()
    {
        return array(
                'from_user' => array(self::BELONGS_TO, 'Member', 'from_user_id'),
                'to_user' => array(self::BELONGS_TO, 'Member', 'to_user_id'),
        );
    }
    
    public function beforeSave(){
        if($this->isNewRecord) {
            $this->created = time();
        }
        return parent::beforeSave();
    }
    
    public function getAnswers(){
        if(!empty($this->id)){
            $criteria = new CDbCriteria();
            $criteria->addCondition("answered = :answered");
            $criteria->params = array(':answered'=>$this->id);
            $answers = $this->findAll($criteria); 
            if($answers){
                return $answers;
            }
        }   
        return false;    
    }
    
    public function getAnswersPagging($page){
    	if(!empty($this->id)){
    		$criteria = new CDbCriteria();
    		$criteria->addCondition("answered = :answered OR id = :answered");
    		$criteria->params = array(':answered'=>$this->id);
    
    		$totaldata = $this->count($criteria);
    		$criteria->order = 'id DESC';
    		
    		$pages = new CPagination($totaldata);
    		$pages->pageSize = CParams::load()->params->page_limit->message_answers;
    		$pages->setCurrentPage($page - 1);
    		$pages->applyLimit($criteria);
    		$next_page = ($totaldata > $pages->pageSize * $page) ? $page + 1 : 0 ;    
    
    		$data = $this->findAll($criteria);
    		if ($data){
    			$data = array_reverse($data);
    			return array('data' => $data, 'next' => $next_page, 'pages' => $pages);
    		}
    	}
    	return false;
    }
        
    public function getAnswerLasted(){
        if(!empty($this->id)){
            $criteria = new CDbCriteria();
            $criteria->addCondition("answered = :answered");
            $criteria->params = array(':answered'=>$this->id);
            $criteria->order = 'id DESC';
            $answers = $this->find($criteria);
            if($answers){
                return $answers;
            }
        }
        return false;
    }
    
    public function getAnswerLastedToUser($me){
        if(!empty($this->id)){
            $criteria = new CDbCriteria();
            $criteria->addCondition("answered = :answered AND to_user_id = :to");
            $criteria->params = array(':answered'=>$this->id, ':to'=>$me);
            $criteria->order = 'id DESC';
            $answers = $this->find($criteria);
            if($answers){
                return $answers;
            }
        }
        return false;
    }
    
    public static function write($to, $from, $subject, $body, $mail = true, $is_system = false) {
        $message = new Message();
    
        if(!$mail)
            $message->omit_mail = true;
    
        if(is_object($from))
            $message->from_user_id = (int) $from->id;
        else if(is_numeric($from))
            $message->from_user_id = $from;
        else if(is_string($from)
        && $user = YumUser::model()->find("username = '{$from}'"))
            $message->from_user_id = $user->id;
        else
            return false;
    
        if(is_object($to))
            $message->to_user_id = (int) $to->id;
        else if(is_numeric($to))
            $message->to_user_id = $to;
        else if(is_string($to)
        && $user = YumUser::model()->find("username = '{$to}'"))
            $message->to_user_id = $user->id;
        else
            return false;
    
        $message->title = $subject;
        $message->message = $body;
        $message->message_read = Message::UNREAD_TO;
        $message->timestamp = time();
        $message->is_system = $is_system;
        
        return $message->save();
    }
    
    public function read(){
        if(!empty($this->id) && ($this->message_read == Message::UNREAD_TO || $this->message_read == Message::UNREAD_FROM)){
            $this->message_read = Message::READ;
            return $this->save();
        }
    }
    
    public function getTotalSendByUser($user_id){
        $criteria = new CDbCriteria();
        $criteria->alias = 'm';
        $criteria->addCondition("m.id in (
        		SELECT id FROM (
        			SELECT id FROM `usr_message` WHERE is_system = 0 AND (from_user_id = :from OR to_user_id = :to) AND answered = 0 AND `status` != :status3 AND ((from_user_id = :from AND `status` != :status1) OR (to_user_id = :to AND `status` != :status2) OR (`status` = :status)) 
        		ORDER BY id ASC ) as tbl)");
        $criteria->params = array(':from'=>$user_id, ':to'=>$user_id, ':status' => Message::STATUS_ENABLE, ':status1' => Message::DELETE_FROM, ':status2' => Message::DELETE_TO , ':status3' => Message::DELETE_ALL);
        $total = Message::model()->count($criteria);
        if(!empty($total)){
            return $total;
        }
        return 0;
    }
    
    public function _getMsgCDbCriteria($user_id){
    	$sqlReply = '(CASE 
				        WHEN EXISTS(SELECT m2.message FROM `usr_message` m2 WHERE m2.answered = m.id ORDER BY m2.`timestamp` DESC LIMIT 1) THEN (SELECT m2.message FROM `usr_message` m2 WHERE m2.answered = m.id ORDER BY m2.`timestamp` DESC LIMIT 1)				        
				        ELSE m.message
				    END) AS message';
    	$sqlTypeMsg = '(CASE 
				        WHEN EXISTS(SELECT m2.to_user_id FROM `usr_message` m2 WHERE m2.answered = m.id ORDER BY m2.`timestamp` DESC LIMIT 1) THEN (SELECT m2.to_user_id FROM `usr_message` m2 WHERE m2.answered = m.id ORDER BY m2.`timestamp` DESC LIMIT 1)				        
				        ELSE m.to_user_id
				    END) AS to_user_reply';
        $criteria=new CDbCriteria();
        $criteria->alias = 'm';
        $criteria->select = array('m.id,m.timestamp,m.from_user_id,m.to_user_id,m.message,m.message_read,m.answered,m.draft,m.status,m.is_system');
//         $criteria->select = array('*', $sqlReply, $sqlTypeMsg);
        $criteria->addCondition("(from_user_id = :from OR to_user_id = :to) AND answered = 0 AND `status` != :status3 AND ((from_user_id = :from AND `status` != :status1) OR (to_user_id = :to AND `status` != :status2) OR (`status` = :status))");
        $criteria->params = array(':from'=>$user_id, ':to'=>$user_id, ':status' => Message::STATUS_ENABLE, ':status1' => Message::DELETE_FROM, ':status2' => Message::DELETE_TO , ':status3' => Message::DELETE_ALL);
        $criteria->order='m.timestamp DESC';
    	return $criteria;
    }
    
    public function getConversation($user_id, $page){
    	$criteria = $this->_getMsgCDbCriteria($user_id);
    	
    	$totaldata = $this->count($criteria);
    	
    	$pages = new CPagination($totaldata);
    	$pages->pageSize = CParams::load()->params->page_limit->message_conversation;
    	$pages->setCurrentPage($page - 1);
    	$pages->applyLimit($criteria);
    	$next_page = ($totaldata > $pages->pageSize * $page) ? $page + 1 : 0 ;
    	
    	$data = $this->findAll($criteria);
    	if ($data){
    		return array('data' => $data, 'next' => $next_page, 'pages' => $pages);
    	}
        return false;
    }
    
    public function getMsgCDbCriteria($user_id, $limit, $offset){
    	$criteria = $this->_getMsgCDbCriteria($user_id);
        $criteria->limit = $limit;
        $criteria->offset = $offset;
        return $criteria;
    }
    
    public function getTotal($criteria){
        $total = Message::model()->count($criteria);
        if(!empty($total)){
            return $total;
        }
        return 0;
    }
    
    public function getMsgEnableByUser($criteria){
        $messages = Message::model()->findAll($criteria);
        if(!empty($messages)){
            return $messages;
        }
        return false;
    }
    
    public function getAllMsgByUser($user_id, $limit, $offset){
        $criteria = new CDbCriteria();
        $criteria->addCondition("(from_user_id = :from OR to_user_id = :to) AND answered = 0");
        $criteria->params = array(':from'=>$user_id, ':to'=>$user_id);
        $criteria->order = 'created DESC';
        $criteria->limit = $limit;
        $criteria->offset = $offset;
        $messages = Message::model()->findAll($criteria);
        if(!empty($messages)){
            return $messages;
        }
        return false;
    }
    
    public function getQuotas($user_id){
        $return = array('total'=>0, 'limit'=>Yii::app()->config->get('message_quotas'), 'percent'=>0);
        if(!empty($user_id)){
            $total = $this->getTotalSendByUser($user_id);
            $return['total'] = $total;
        }
        $percent1 = ($total/$return['limit'] > 1) ? 1 : $total/$return['limit'];
        if(!empty($percent1)){
            $percent = ($percent1)*100;
            $return['percent'] = number_format($percent, 0);
        }
        return $return;
    }
    
    public function fromMe(){
        if(!empty($this->id) && $this->from_user_id == Yii::app()->user->id){
            return true;
        }
        return false;
    }
    
    public function toMe(){
        if(!empty($this->id) && $this->to_user_id == Yii::app()->user->id){
            return true;
        }
        return false;
    }

}
