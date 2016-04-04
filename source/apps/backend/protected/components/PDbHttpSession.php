<?php
class PDbHttpSession extends CDbHttpSession {
	
		
	private $_db;
	
	/**
	 * Do not create SessionTable, cause it is modified.
	 * This method overrides the parent implementation and always returns true.
	 * @return boolean whether to use create session table.
	 */
	public function createSessionTable($db,$tableName){
		
		return true;
		
	}
	
	/**
	 * Session read handler.
	 * Do not call this method directly.
	 * @param string $id session ID
	 * @return string the session data
	 */
	public function readSession($id)
	{
		$db=$this->getDbConnection();
		if($db->getDriverName()=='sqlsrv' || $db->getDriverName()=='mssql' || $db->getDriverName()=='dblib')
			$select='CONVERT(VARCHAR(MAX), data)';
		else
			$select='data'; //select * from {session_tables}
		$data=$db->createCommand()
			->select($select)
			->from($this->sessionTableName)
			->where('expire>:expire AND id=:id',array(':expire'=>time(),':id'=>$id))
			->queryScalar();
		return $data===false?'':$data;
	}
	
	
	/**
	 * Session write handler.
	 * Do not call this method directly.
	 * @param string $id session ID
	 * @param string $data session data
	 * @return boolean whether session write is successful
	 */
	public function writeSession($id,$data)
	{
		
		// exception must be caught in session write handler
		// http://us.php.net/manual/en/function.session-set-save-handler.php
		try
		{
			$expire=time()+$this->getTimeout();
			$db=$this->getDbConnection();
			if($db->getDriverName()=='sqlsrv' || $db->getDriverName()=='mssql' || $db->getDriverName()=='dblib')
				$data=new CDbExpression('CONVERT(VARBINARY(MAX), '.$db->quoteValue($data).')');
			if($db->createCommand()->select('id')->from($this->sessionTableName)->where('id=:id',array(':id'=>$id))->queryScalar()===false)
				$db->createCommand()->insert($this->sessionTableName,array(
						'id'=>$id,
						'data'=>$data,
						'expire'=>$expire,
						'ip_address'=>CHttpRequest::getUserHostAddress(),
						'user_agent'=>CHttpRequest::getUserAgent(),
						'created'=> time(),
				));
			else
				$db->createCommand()->update($this->sessionTableName,array(
						'data'=>$data,
						'expire'=>$expire,
						'ip_address'=>CHttpRequest::getUserHostAddress(),
						'user_agent'=>CHttpRequest::getUserAgent(),
						'updated'=> time(),
						
				),'id=:id',array(':id'=>$id));
		}
		catch(Exception $e)
		{
			if(YII_DEBUG)
				echo $e->getMessage();
			// it is too late to log an error message here
			return false;
		}
		return true;
	}
	
	
	protected function getDbConnection()
	{
		if($this->_db!==null)
			return $this->_db;
		elseif(($id=$this->connectionID)!==null)
		{
			if(($this->_db=Yii::app()->getComponent($id)) instanceof CDbConnection)
				return $this->_db;
			else
				throw new CException(Yii::t('yii','CDbHttpSession.connectionID "{id}" is invalid. Please make sure it refers to the ID of a CDbConnection application component.',
						array('{id}'=>$id)));
		}
		else
		{
			$dbFile=Yii::app()->getRuntimePath().DIRECTORY_SEPARATOR.'session-'.Yii::getVersion().'.db';
			return $this->_db=new CDbConnection('sqlite:'.$dbFile);
		}
	}
}