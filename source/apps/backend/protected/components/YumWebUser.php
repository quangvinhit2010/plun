<?

class YumWebUser extends CWebUser
{

    public $_data;
    
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

    public function can ($action)
    {
        Yii::import('application.modules.role.models.*');
        foreach ($this->data()->getPermissions() as $permission)
            if ($permission == $action)
                return true;
        
        return false;
    }

    /**
     * Checks if this (non-admin) User can administrate some users
     */
    public static function hasUsers ($uid = 0)
    {
        if ($uid == 0)
            $uid = Yii::app()->user->id;
        
        $user = YumUser::model()->cache(500)->findByPk($uid);
        
        return isset($user->users) && $user->users !== array();
    }

    public static function hasRoles ($uid = 0)
    {
        if ($uid == 0)
            $uid = Yii::app()->user->id;
        
        $user = YumUser::model()->cache(500)->findByPk($uid);
        
        $flag = false;
        if (isset($user->roles))
            foreach ($user->roles as $role)
                if (isset($role->roles) && $role->roles !== array())
                    $flag = true;
        
        return $flag;
    }

    public function getRoles ()
    {
        $t = ' ';
        $user = Yii::app()->user->data();
        $roles = $user->roles;
        if ($user instanceof YumUser && $roles)
            foreach ($roles as $role)
                $t .= $role->title . ' ';
        
        return $t;
    }

    /**
     * Checks if this (non-admin) User can administrate the given user
     */
    public static function hasUser ($username, $uid = 0)
    {
        if ($uid == 0)
            $uid = Yii::app()->user->getId();
            
            // Every user can modify himself
        if ($username == $uid)
            return true;
        
        $user = YumUser::model()->cache(500)->findByPk($uid);
        
        if (! is_array($username))
            $username = array(
                    $username
            );
        
        if (isset($user->users))
            foreach ($user->users as $userobj) {
                if (in_array($userobj->username, $username) ||
                         in_array($userobj->id, $username))
                    return true;
            }
        return false;
    }

    /**
     * Checks if the user has the given Role
     * @mixed Role string or array of strings that should be checked
     * @int (optional) id of the user that should be checked
     * 
     * @return bool Return value tells if the User has access or hasn't access.
     */
    public function hasRole ($role, $uid = 0)
    {
        if (Yum::hasModule('role')) {
            Yii::import('application.modules.role.models.*');
            
            if ($uid == 0)
                $uid = Yii::app()->user->id;
            
            if (! is_array($role))
                $role = array(
                        $role
                );
            
            if ($uid && $user = YumUser::model()->with('roles')->find(
                    't.id = ' . $uid)) {
                // Check if a user has a active membership and, if so, add this
                // to the roles
                $roles = $user->roles;
                if (Yum::hasModule('membership'))
                    $roles = array_merge($roles, $user->getActiveMemberships());
                
                if (isset($roles))
                    foreach ($roles as $roleobj) {
                        if (in_array($roleobj->title, $role) ||
                                 in_array($roleobj->id, $role))
                            return true;
                    }
            }
        }
        
        return false;
    }

    public function loggedInAs ()
    {
        if ($this->isGuest)
            return Yum::t('Guest');
        else
            return $this->data()->username;
    }

    /**
     * Return admin status.
     * 
     * @return boolean
     */
    public function isAdmin ()
    {
        if ($this->isGuest)
            return false;
        else
            return Yii::app()->user->data()->superuser;
    }

    /**
     * Actions to be taken after logging in.
     * Overloads the parent method in order to mark superusers.
     * 
     * @param boolean $fromCookie
     *            whether the login is based on cookie.
     */
    public function afterLogin ($fromCookie)
    {
        parent::afterLogin($fromCookie);
        
        // Mark the user as a superuser if necessary.
        if (Rights::getAuthorizer()->isSuperuser($this->getId()) === true)
            $this->isSuperuser = true;
    }

    /**
     * Performs access check for this user.
     * Overloads the parent method in order to allow superusers access
     * implicitly.
     * 
     * @param string $operation
     *            the name of the operation that need access check.
     * @param array $params
     *            name-value pairs that would be passed to business rules
     *            associated
     *            with the tasks and roles assigned to the user.
     * @param boolean $allowCaching
     *            whether to allow caching the result of access checki.
     *            This parameter has been available since version 1.0.5. When
     *            this parameter
     *            is true (default), if the access check of an operation was
     *            performed before,
     *            its result will be directly returned when calling this method
     *            to check the same operation.
     *            If this parameter is false, this method will always call
     *            {@link CAuthManager::checkAccess}
     *            to obtain the up-to-date access result. Note that this caching
     *            is effective
     *            only within the same request.
     * @return boolean whether the operations can be performed by this user.
     */
    public function checkAccess ($operation, $params = array(), $allowCaching = true)
    {
        // Allow superusers access implicitly and do CWebUser::checkAccess for
        // others.
        return $this->isSuperuser === true ? true : parent::checkAccess(
                $operation, $params, $allowCaching);
    }

    /**
     *
     * @param boolean $value
     *            whether the user is a superuser.
     */
    public function setIsSuperuser ($value)
    {
        $this->setState('Rights_isSuperuser', $value);
    }

    /**
     *
     * @return boolean whether the user is a superuser.
     */
    public function getIsSuperuser ()
    {
        return $this->getState('Rights_isSuperuser');
    }

    /**
     *
     * @param array $value
     *            return url.
     */
    public function setRightsReturnUrl ($value)
    {
        $this->setState('Rights_returnUrl', $value);
    }

    /**
     * Returns the URL that the user should be redirected to
     * after updating an authorization item.
     * 
     * @param string $defaultUrl
     *            the default return URL in case it was not set previously. If
     *            this is null,
     *            the application entry URL will be considered as the default
     *            return URL.
     * @return string the URL that the user should be redirected to
     *         after updating an authorization item.
     */
    public function getRightsReturnUrl ($defaultUrl = null)
    {
        if (($returnUrl = $this->getState('Rights_returnUrl')) !== null)
            $this->returnUrl = null;
        
        return $returnUrl !== null ? CHtml::normalizeUrl($returnUrl) : CHtml::normalizeUrl(
                $defaultUrl);
    }
}
?>
