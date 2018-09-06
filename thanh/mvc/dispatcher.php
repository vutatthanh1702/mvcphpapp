<?php
require_once "./PHPTAL-1.3.0/PHPTAL.php";
class Dispatcher
{
	private $sysRoot;
    
    public function setSystemRoot($path)
    {
        $this->sysRoot = rtrim($path, '/');
    }
	public function getPathInfo()
	{
		if (array_key_exists('PATH_INFO', $_SERVER)) {
			return $_SERVER['PATH_INFO'];
		}
		$path_info = str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['REQUEST_URI']);
		if (substr_count($path_info, '?') > 0) {
			// GETパラメータを除去
			$path_info = preg_replace('/\?.*/', '', $path_info);
		}
		return $path_info;
	}
    public function dispatch()
    {	
		$basepath=$_SERVER["SCRIPT_NAME"];
        $basepath = preg_replace('/index.php/', '', $basepath);
        $path_info = str_replace($basepath, '', $_SERVER['REQUEST_URI']);
        $params = array();
		$params = explode('/', $path_info);
		$outerDir=$this->sysRoot.'/controllers/';
		$dirs = array_diff( scandir( $outerDir ), Array( ".", ".." ) );
		#var_dump($dirs);
		foreach ($dirs as &$value) {
			$value =str_replace(".php", "", $value);
		}
		unset($value);
		#var_dump($dirs);
		for($i=2; $i<=8; $i++){
			if($dirs[$i]==$params[0]){
            	$exis = 1;
            }
		}
		if($exis == 1&&0 < count($params)){
            $controller = $params[0];
        }else{
            $controller = 'signup';
        }
		#var_dump($controller);	
        // １番目のパラメーターをコントローラーとして取得
		$shiftController=0;
		$shiftAction=$shiftController+1;
		$className = $controller;
		require_once $this->sysRoot . '/controllers/' . $className . '.php';        
        // パラメータより取得したコントローラー名によりクラス振分け
        $controllerInstance = null;
		$className = $controller.'Controller';
		$controllerInstance = new $className();
		$action= 'get_list';
        if (1 < count($params)&&$exis == 1) {
            $action= $params[$shiftAction];
        }
		$path=$this->sysRoot;        
        // アクションメソッドを実行
        $actionMethod = $action;
		$controllerInstance->setSystemRoot($path);
		$controllerInstance->$actionMethod();
		
    }
}

?>
