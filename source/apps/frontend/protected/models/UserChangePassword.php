<?
/**
 * UserChangePassword class.
 * UserChangePassword is the data structure for keeping
 * user change password form data. It is used by the 'changepassword' action
 * of 'UserController'.
 */

class UserChangePassword extends CFormModel 
{
//	private $_errors;
	public $password;
	public $verifyPassword;

	public function addError($attribute, $error) {
		parent::addError($attribute, Yum::t($error));
	}

	public function rules() {
		$passwordRequirements = Yum::module()->passwordRequirements;
// 		$passwordrule = array_merge(array(
// 					'password', 'YumPasswordValidator'), $passwordRequirements);
// 		$rules[] = $passwordrule;
		$rules[] = array('password, verifyPassword', 'required');
		$rules[] = array('verifyPassword', 'compare', 'compareAttribute' =>'password', 'message' => Lang::t('yii', "Confirm password is incorrect."));

		return $rules;
	}

	public function attributeLabels() {
		return array(
			'password'=>Lang::t('forgot', 'New Password'),
			'verifyPassword'=>Lang::t('forgot', 'Verify Password'),
		);
	}

	public static function createRandomPassword() {
		$lowercase = Yum::module()->passwordRequirements['minLowerCase'];
		$uppercase = Yum::module()->passwordRequirements['minUpperCase'];
		$minnumbers = Yum::module()->passwordRequirements['minDigits'];
		$max = Yum::module()->passwordRequirements['maxLen'];

		$chars = "abcdefghijkmnopqrstuvwxyz";
		$numbers = "1023456789";
		srand((double) microtime() * 1000000);
		$i = 0;
		$current_lc = 0;
		$current_uc = 0;
		$current_dd = 0;
		$password = '';
		while ($i <= $max) {
			if ($current_lc < $lowercase) {
				$charnum = rand() % 22;
				$tmpchar = substr($chars, $charnum, 1);
				$password = $password . $tmpchar;
				$i++;
			}

			if ($current_uc < $uppercase) {
				$charnum = rand() % 22;
				$tmpchar = substr($chars, $charnum, 1);
				$password = $password . strtoupper($tmpchar);
				$i++;
			}

			if ($current_dd < $minnumbers) {
				$charnum = rand() % 9;
				$tmpchar = substr($numbers, $charnum, 1);
				$password = $password . $tmpchar;
				$i++;
			}

			if ($current_lc == $lowercase && $current_uc == $uppercase && $current_dd == $numbers && $i < $max) {
				$charnum = rand() % 22;
				$tmpchar = substr($chars, $charnum, 1);
				$password = $password . $tmpchar;
				$i++;
				if ($i < $max) {
					$charnum = rand() % 9;
					$tmpchar = substr($numbers, $charnum, 1);
					$password = $password . $tmpchar;
					$i++;
				}
				if ($i < $max) {
					$charnum = rand() % 22;
					$tmpchar = substr($chars, $charnum, 1);
					$password = $password . strtoupper($tmpchar);
					$i++;
				}
			}
		}
		return $password;
	}
}
