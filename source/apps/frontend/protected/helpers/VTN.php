<?php
/**
 * @author VSoft
 */
class VTN {
	private $client;
    private static $_models = array ();
    /**
     * Model
     *
     * @param system $className
     * @return multitype: unknown
    */
    public static function model($className = __CLASS__) {
        if (isset ( self::$_models [$className] ))
            return self::$_models [$className];
        else {
            $model = self::$_models [$className] = new $className ( null );
            return $model;
        }
    }
    
    public function __construct(){
    	$this->client = array(
    			'api_s'=>'76ec9eec61e7fdfef2f3feee28d5f392',
    			'apiclientid'=>'1',
    			'api_v'=>'1',
    			'secret'=>'33458e96a0b4ef9504757dc388b18909',
    			'apikey'=>CParams::load()->params->vtn->apikey,
    	);
    }
    
    public function updateUser($data){
    	if(empty($data) || empty($data['username'])){
    		return false;
    	}
    	$client = $this->client;
    	$post = array(
    			'api_m'=>'plun_updatepass',
    			'api_c'=>$client['apiclientid'],
    			'api_s'=>$client['api_s'],
    			'email'=>!empty($data['email']) ? Util::mcrypt_encrypt($data['email']) : '',
    			'password'=>!empty($data['password']) ? Util::mcrypt_encrypt($data['password']) : '',
    			'username'=>Util::mcrypt_encrypt($data['username']),
    	);
    	$signtoverify = md5($client['api_s'] . $client['apiclientid'] . $client['secret'] . $client['apikey']);
    	$post['api_sig'] = $signtoverify;
    	 
    	$url = CParams::load()->params->vtn->url."/forum/api.php";
    	$output = Yii::app()->curl
    	->setOption(CURLOPT_HEADER, false)
    	->setOption(CURLOPT_HTTPHEADER, array( 'Expect:', 'X-API-Key: 24241324'))
    	->setOption(CURLOPT_FOLLOWLOCATION, false)
    	->setOption(CURLOPT_RETURNTRANSFER, true)
    	->post($url, http_build_query($post));
    	$return = CJSON::decode($output, false);
    	return $return;
    }
    
    public function createUser($data){
    	$client = $this->client;
    	$post = array(
    			'api_m'=>'plun_createuser',
    			'api_c'=>$client['apiclientid'],
    			'api_s'=>$client['api_s'],
    			'username'=>Util::mcrypt_encrypt($data['username']),
    			'password'=>Util::mcrypt_encrypt($data['password']),
    			'email'=>Util::mcrypt_encrypt($data['email']),
    			'birthday'=>Util::mcrypt_encrypt($data['birthday']),
    			'pid'=>Util::mcrypt_encrypt($data['pid']),
    	);
    	$post['api_sig'] = md5($client['api_s'] . $client['apiclientid'] . $client['secret'] . $client['apikey']);
    	 
    	$url = CParams::load()->params->vtn->url."/forum/api.php";
    	$output = Yii::app()->curl
    	->setOption(CURLOPT_HEADER, false)
    	->setOption(CURLOPT_HTTPHEADER, array( 'Expect:', 'X-API-Key: 24241324'))
    	->setOption(CURLOPT_FOLLOWLOCATION, false)
    	->setOption(CURLOPT_RETURNTRANSFER, true)
    	->post($url, http_build_query($post));
    	$return = CJSON::decode($output, false);
    	return $return;
    }
    
    public function validate($data){
    	$client = $this->client;
    	$post = array(
    			'api_m'=>'plun_validateuser',
    			'api_c'=>$client['apiclientid'],
    			'api_s'=>$client['api_s'],
    			'username'=>Util::mcrypt_encrypt($data['username']),
    			'email'=>Util::mcrypt_encrypt($data['email']),
    	);
    	$post['api_sig'] = md5($client['api_s'] . $client['apiclientid'] . $client['secret'] . $client['apikey']);
    	
    	$url = CParams::load()->params->vtn->url."/forum/api.php";
    	$output = Yii::app()->curl
    	->setOption(CURLOPT_HEADER, false)
    	->setOption(CURLOPT_HTTPHEADER, array( 'Expect:', 'X-API-Key: 24241324'))
    	->setOption(CURLOPT_FOLLOWLOCATION, false)
    	->setOption(CURLOPT_RETURNTRANSFER, true)
    	->post($url, http_build_query($post));
    	$return = CJSON::decode($output, false);
    	return $return;
    }
} 