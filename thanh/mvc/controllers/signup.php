<?php
require_once "./library/sys.php";
class signupController extends setSys
    {  
	public function get_list()
    {
    #require 'config.php';
	if (isset($_POST['up'])) {//ouputing inputed data to signup.php from signup.php itself or signup_check.php
	 if(isset($_POST['username']) ){
		$username = htmlspecialchars($_POST['username'],ENT_QUOTES);
		$password = htmlspecialchars($_POST['password'],ENT_QUOTES);
		$password1 = hash("sha256",$password);
		$passw = "ewweg".$password1."asdfe";
		$passd = hash("sha256",$passw);
		session_start();
		$_SESSION['username']=htmlspecialchars($_POST['username']);
		$_SESSION['password']=$passd;
		$_SESSION['password1']=$password;
		$row['username']=$_SESSION['username'];
		$row['password']=$_SESSION['password1'];
		session_destroy();
	}				
	else{// check wrong place without touching to sever
		session_start();
		$row['username']=$_SESSION['username'];
		$row['password']=$_SESSION['password1'];
		session_destroy();
	}
	if ($_POST['password'] != $_POST['passwordauth'] ) {
			$error = 'パスワードの再確認が正しくありません';
	}
	//----------------------
	//空ならエラー
	//----------------------
	if ($username == "" ) {
		$error = 'IDが入っていません';
	 }
	if ($password == "" ) { 
		$error = 'パスワードが入っていません';
	 }
	//----------------------
	//文字数確認
	//----------------------
	$sid = strlen($username);
	$spass = strlen($password);
	if ($sid < 4 ){ 
		$error = 'IDは４文字以上で設定してください';
	 }
	if ($spass < 4 ){
		 $error = 'パスワードは４文字以上で設定してください';
	 }
	//----------------------
	//プレグマッチ
	//----------------------
	if(preg_match("/^[a-zA-Z0-9]+$/", $password)){
		$password = $password;
	 }else{
		$error = 'パスワードは半角英数で登録してください'; }
	if (preg_match("/^[a-zA-Z0-9]+$/", $username)) {
		$username = $username;
	 }else{
		$error = 'IDは半角英数で登録してください。';
	 }
	//---------------------
	//重複チェック
	//---------------------
	#$stmt = $db->query("SELECT * FROM members");
	#while($item = $stmt->fetch()) {
	#	if($item['username'] == $username){
	#	$error = '<p class="error">ご希望のIDは既に使用されています。</p>';
	#	}else{
	# $id = $id;
	# }
	#}
	if ($error == "" ) {//send username and password to server and redirect to signup_check.php
		session_start();
		$_SESSION['username']=htmlspecialchars($_POST['username']);
		$_SESSION['password']=$passd;
		$_SESSION['password1']=$password;
		header('Location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/signup/check');
		}
	}

	if (isset($row)) {
	}else{
		$row['up']=NULL;
		$row['username']='';
		$row['password']='';
	}

	require_once './PHPTAL-1.3.0/PHPTAL.php';// create a new template object
	$template = new PHPTAL($this->sysRoot . '/view/' .'signup1.html');
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
	
	

	public function check()
    {
	session_start();
    $title1=$_SESSION['username'];
    $memo1=$_SESSION['password'];
    //-------------------
    //DBに登録
    //-------------------
    if (isset($_POST['write'])) {
    require $this->sysRoot . '/model/' . 'Connection.php';
        if ($error == "" ) {
            $db = new Member();
            $result = $db -> register();
            header('Location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/login'.'/signin');
        }//var_dump($Connection);
    }
    require_once './PHPTAL-1.3.0/PHPTAL.php';// create a new template object
    $template = new PHPTAL($this->sysRoot . '/view/' .'signup_check1.html');
    $template->title = $title1;
    try {
        echo $template->execute();
    }
    catch (Exception $e){
        echo $e;
    }
	}


}
