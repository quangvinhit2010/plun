<?
class YumWebUser extends CWebUser
{
	public $_data;
	
	//add by NAM LE
	private $_db_session;
	
	
	// Use this function to access the AR Model of the actually
	// logged in user, for example
	// echo Yii::app()->user->data()->profile->firstname;
	public function data() {
		if($this->_data instanceof Member)
			return $this->_data;
		else if($this->id && $this->_data = Member::model()->findByPk($this->id))
			return $this->_data;
		else
			return $this->_data = new Member();
	}

    public function checkAccess ($operation, $params = array(), $allowCaching = true)
    {
        // Allow superusers access implicitly and do CWebUser::checkAccess for
        // others.
        return $this->isAdmin() == true ? true : parent::checkAccess(
                $operation, $params, $allowCaching);
    }

	public function can($action) {
		Yii::import('application.modules.role.models.*');
		foreach ($this->data()->getPermissions() as $permission)
			if ($permission == $action)
				return true;

		return false;
	}

	/**
	 * Checks if this (non-admin) User can administrate some users
	 */
	public static function hasUsers($uid = 0)
	{
		if($uid == 0)
			$uid = Yii::app()->user->id;

		$user = Member::model()->cache(500)->findByPk($uid);

		return isset($user->users) && $user->users !== array();
	}

	public static function hasRoles($uid = 0)
	{
		if($uid == 0)
			$uid = Yii::app()->user->id;

		$user = Member::model()->cache(500)->findByPk($uid);

		$flag = false;
		if(isset($user->roles))
			foreach($user->roles as $role) 
				if (isset($role->roles) && $role->roles !== array())
					$flag = true;

		return $flag;
	}

	public function getRoles() {
		$t = ' ';
		$user = Yii::app()->user->data();
		$roles = $user->roles;
		if($user instanceof Member && $roles) 
			foreach($roles as $role)
				$t .= $role->title .' ';
	
		return $t;
	
	}

	/**
	 * Checks if this (non-admin) User can administrate the given user
	 */
	public static function hasUser($username, $uid = 0)
	{
		if($uid == 0)
			$uid = Yii::app()->user->getId();

		// Every user can modify himself
		if($username == $uid)
			return true;

		$user = Member::model()->cache(500)->findByPk($uid);

		if(!is_array($username))
			$username = array ($username);

		if(isset($user->users)) 
			foreach($user->users as $userobj) 
			{
				if(in_array($userobj->username, $username) ||
					in_array($userobj->id, $username))
					return true;
			}
		return false;
	}

	/**
	 * Checks if the user has the given Role
	 * @mixed Role string or array of strings that should be checked
	 * @int (optional) id of the user that should be checked 
	 * @return bool Return value tells if the User has access or hasn't access.
	 */
	public function hasRole($role, $uid = 0) {
		if(Yum::hasModule('role')) {
			Yii::import('application.modules.role.models.*');

			if($uid == 0)
				$uid = Yii::app()->user->id;

			if(!is_array($role))
				$role = array ($role);

			if($uid && $user = Member::model()->with('roles')->find(
						't.id = '.$uid)) {
				// Check if a user has a active membership and, if so, add this
				// to the roles
				$roles = $user->roles;
				if(Yum::hasModule('membership'))
					$roles = array_merge($roles, $user->getActiveMemberships());

				if(isset($roles)) 
					foreach($roles as $roleobj) {
						if(in_array($roleobj->title, $role) ||
								in_array($roleobj->id, $role))
							return true;
					}
			}
		}

		return false;
	}

	public function loggedInAs() {
		if($this->isGuest)
			return Yum::t('Guest');
		else
			return $this->data()->username;
	}
	/**
	 * Return admin status.
	 * @return boolean
	 */
	public function isAdmin() {
		if($this->isGuest)
			return false;
		else 
			return Yii::app()->user->data()->superuser;
	}
	
	/**
	 * Get onlines list 
	 */
	public function listOnline(){
		if($this->isGuest) {
			return false;
		} else {
			
			$db = explode('=', Yii::app()->db->connectionString);
			$db = $db['2'];
			$db_active = explode('=', Yii::app()->db_activity->connectionString);
			$db_active = $db_active['2'];
			$user_id = Yii::app()->user->id;
			$select = "s.user_id, u.id, u.username, u.avatar, u.alias_name, s.expire, s.ip_address, s.user_agent, s.id as sid";
			
			$sql ="SELECT ".$select." FROM (
			SELECT inviter_id AS user_id  FROM `".$db."`.`usr_friendship` WHERE (inviter_id = ".$user_id." OR friend_id=".$user_id.") AND status = 2
			UNION ALL
			SELECT friend_id AS user_id  FROM `".$db."`.`usr_friendship` WHERE (inviter_id = ".$user_id." OR friend_id=".$user_id.") AND status = 2) AS tbl 
			INNER JOIN `".$db."`.`usr_user` u ON u.id = tbl.user_id
			INNER JOIN `".$db."`.`usr_profile` p ON p.user_id = tbl.user_id 
			INNER JOIN `".$db_active."`.`activities_sessions` s ON s.user_id = tbl.user_id 
			WHERE s.updated > (UNIX_TIMESTAMP() - 900) AND tbl.user_id NOT IN (".$user_id.")  GROUP BY tbl.user_id";
			$connection	=	Yii::app()->db;
			$command	=	$connection->createCommand($sql);
			$result = $command->queryAll(); // execute a query SQL
			return $result;
		}
		
	}
	
	
	protected function afterLogin($fromCookie)
	{
		
		$db	=	Yii::app()->db_activity;
		if($db->createCommand()->select('id')->from(Yii::app()->session->sessionTableName)->where('id=:id',array(':id'=>Yii::app()->session->sessionID))->queryScalar()){
			$db->createCommand()->update(Yii::app()->session->sessionTableName,array(
					'user_id'=> ($this->data()->id) ? $this->data()->id : 0,
					'updated'=> time(),
			
			),'id=:id',array(':id'=> Yii::app()->session->sessionID));
		}
	}
	
	protected function afterLogout()
	{
		
		$db	=	Yii::app()->db_activity;
		if($db->createCommand()->select('id')->from(Yii::app()->session->sessionTableName)->where('id=:id',array(':id'=>Yii::app()->session->sessionID))->queryScalar()){
			$db->createCommand()->update(Yii::app()->session->sessionTableName,array(
					'user_id'=> 0,
					'updated'=> time(),
			
			),'id=:id',array(':id'=> Yii::app()->session->sessionID));
		}
	}
}
?>
