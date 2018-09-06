<?php
require_once "./library/sys.php";
require_once "./PHPTAL-1.3.0/PHPTAL.php";
class loginController extends setSys
    { 
    public function signin()
    {
	$error='';
	if (isset($_POST['in'])) {
	if(isset($_POST['username']) ){
		$username  = htmlspecialchars($_POST['username'],ENT_QUOTES);
		$password  = htmlspecialchars($_POST['password'],ENT_QUOTES);
		$password1 = hash("sha256",$password);
		$passw     = "ewweg".$password1."asdfe";
		$passd     = hash("sha256",$passw);
		session_start();
		$_SESSION['username']  =htmlspecialchars($_POST['username']);
		$_SESSION['password']  =$passd;
		$_SESSION['password1'] =$password;
		$row['username']=$_SESSION['username'];
		$row['password']=$_SESSION['password1'];

	}
	else{// check wrong place without touching to sever
		session_start();
		$row['username']=$_SESSION['username'];
		$row['password']=$_SESSION['password1'];
		unset($_SESSION['username']);
		unset($_SESSION['password1']);
	}


	//--------------------------------------
	//入力確認
	//--------------------------------------
	 if (empty($_POST["username"])) {
	
		 $error = 'IDを入力してください';
	 }
	 if (empty($_POST["password"])){ 
		 $error = 'パスワードを入力してください';
	 }
	//-------------------------------------
	//半角英数チェック
	//-------------------------------------
	 if (preg_match("/^[a-zA-Z0-9]+$/", $password)) { 
		 $password = $password;
	 }else{
		 $error = 'パスワードが不正です'; }
	 if (preg_match("/^[a-zA-Z0-9]+$/", $username)) {
		 $username = $username;
	 }else{
		 $error = 'IDが不正です';
		 $row['username']='';
         $row['password']='';
	 }
	//--------------------------------------
	//IDパスの一致確認
	//--------------------------------------
	 if ($error==""){
	    require $this->sysRoot . '/model/' . 'auth.php';
            $db = new auth();
            $result = $db -> login($username,$passd);
	}
}
	if (isset($row)) {
	}else{
		$row['up']=NULL;
		$row['username']='';
		$row['password']='';
	}
	require_once './PHPTAL-1.3.0/PHPTAL.php';// create a new template object
	$template = new PHPTAL($this->sysRoot . '/view/' .'login1.html');
	$template->title = 'The title value';
	$template->error = $error;
	$template->username = $row['username'];
	$template->password = $row['password'];
	try {
		echo $template->execute();
	}
	catch (Exception $e){
		echo $e;
	}
	}
}


