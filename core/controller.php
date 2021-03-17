<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\HtmlFormatter;

class Controller {
	public $model;
	public $view;
	protected $log;

	function __construct()
	{
		$this->model = new Model();
		$this->view = new View();
		
		$this->log = new Logger('mylogger');
		$this->log->pushHandler(new StreamHandler('mylog.log', Logger::WARNING));
        $this->log->pushHandler(new StreamHandler('troubles.log', Logger::ALERT));
	}

	public static function get()
	{
	  static $controller = null;
	  if ($controller == null)
		$controller= new Controller();
	  return $controller;
	}

	function createPage(string $viewName)
	{
		$token = null;
		$authorized = $_SESSION["isauth"];		 
		$vk = $_SESSION["isvk"] ;
		
		if ($viewName === 'login.php')
		{
			$token = hash('gost-crypto', random_int(1,999999));
			$_SESSION["CSRF"] = $token;
		}	
		
		$this->view->generate($viewName, $token, $authorized, $vk);
	}

	function registration()
	{
        $err = [];
       
        if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login']))
        {
			$err[] = "Use english alphabet or nums";
			$this->log->error('Use english alphabet or nums');
        } 
        if(strlen($_POST['login']) < 3 || strlen($_POST['login']) > 30)
        {
			$err[] = "log length min 3 and max 30";
			$this->log->error('log length min 3 and max 30');
        }
        if (!count($err)>0)
        {
            $userChecked = $this->model->checkUserExistance($_POST['login']);
			
			if (!$userChecked)
            {
				
				$passwordWithSalt = md5($_POST['password'].MEGASECRET);				
				$createUser = $this->model->createUser($_POST['login'], $passwordWithSalt, 'notVK');
                if ($createUser)
                {
                    print "You were registered <br>";
                }
                else
                {
					print "Bad registration try <br>";
					$this->log->error('Bad registration try');
                } 
                header("Location: /index.php?page=1");
			}
 			else
        	{
				print "<b>There is such user already </b><br>";
				$this->log->error('There is such user already');      
        	} 

		}      
	}
	
	function login()
	{
		$passwordWithSalt = md5($_POST['password'].MEGASECRET);
		$login = $this->model->getUser($_POST['login'], $passwordWithSalt);
		
		if($login)
        {    
			$_SESSION["isauth"] = true;
			header("Location: /index.php?page=1");
        }
        else
        {
			print "<b>Incorrect loggin or password </b><br>";
			$this->log->error('Incorrect loggin or password');      
        }
	}
	
	function authVK()
	{
		$params;

		if (isset($_GET['code'])) {
				$params = array(
					'client_id' => id,
					'client_secret' => key,
					'code' => $_GET['code'],
					'redirect_uri' => uri
				);
		}
		$token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);
			
		if (isset($token['access_token'])) {
        $params = array(
			'uids' => $token['user_id'],
            'fields' => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big',
            'access_token' => $token['access_token']
        );
		}
		
		$userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))."&v=5.21"), true);

		if (isset($userInfo['response'][0]['id'])) {
			$userInfo = $userInfo['response'][0];
			$result = true;
		}

		$userChecked = $this->model->checkUserExistance($userInfo['id']);

		if (!$userChecked)
		{
			$createUser = $this->model->createUser($userInfo['id'], '', 'vk');
		}

		$_SESSION["isvk"] = true;
		$_SESSION["isauth"] = true;
		header("Location: /index.php?page=1");
	}

	function logout()
	{
		$_SESSION["isauth"] = false;
		$_SESSION["isvk"] = false;
		header("Location: /index.php?page=1");
	}
}