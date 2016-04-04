<?php
class StatusCommand extends CConsoleCommand
{
	public function actionConverthtml() {
		$cmd = Yii::app()->db_activity->createCommand();
		$cmd->select("a.*");
		$cmd->from('activities a');
		$cmd->andWhere("`timestamp` > UNIX_TIMESTAMP(DATE_SUB(NOW(),INTERVAL 2 MONTH)) AND `action` = 1");
		
		$dbrows = $cmd->queryAll();
		foreach($dbrows AS $row){
			$param	=	json_decode($row['params'], true);
			$param['{message}']	=	htmlspecialchars_decode($param['{message}']);
			
			$user=Yii::app()->db_activity->createCommand()
			->update('activities', array(
					'params' => json_encode($param),
			), 'id=:id', array(':id'=>$row['id']));
			echo "udate {$row['id']} \n";
		}
	}
}