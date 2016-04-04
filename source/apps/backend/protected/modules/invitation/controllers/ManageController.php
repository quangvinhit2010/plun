<?php
/**
 * Manager invitation
 * @author quangvinh
 */
class ManageController extends Controller
{
	public $inviteModel;
	public function init(){
		$this->inviteModel = new InviteModel();
		return parent::init();
	}
	
	public function actionIndex()
	{
		$model = new SearchInvitedForm();
		if(!empty($_POST['SearchInvitedForm'])){
			$model->attributes = $_POST['SearchInvitedForm'];
			$model->validate();
			if(!$model->hasErrors()){				
				$this->runConsole($model->from_date, $model->to_date);
			}
		}
		
		$InviteBonus=new InviteBonus('search');
		$InviteBonus->unsetAttributes();  // clear any default values
		if(isset($_GET['InviteBonus']))
			$InviteBonus->attributes=$_GET['InviteBonus'];
		
			
		$this->render('index', array(
				'model'=>$model,
				'InviteBonus'=>$InviteBonus,
			)
		);
	}
	
	private function runConsole($fromdate, $todate) {
	    $commandPath = Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . 'commands';
	    $runner = new CConsoleCommandRunner();
	    $runner->addCommands($commandPath);
	    $commandPath = Yii::getFrameworkPath() . DIRECTORY_SEPARATOR . 'cli' . DIRECTORY_SEPARATOR . 'commands';
	    $runner->addCommands($commandPath);
	    $args = array('yiic', 'invitation', 'GetInviterWillBeBonus', '--limit=10 --fromdate="'.$fromdate.'" --todate="'.$todate.'"');
	    ob_start();
	    $runner->run($args);
//	    echo htmlentities(ob_get_clean(), null, Yii::app()->charset);
	}
}