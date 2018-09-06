<?php
require_once "./library/sys.php";
class userController extends setSys
    { 
    public function read()
    {
		require_once $this->sysRoot . '/controllers/' . 'Encode.php';
		// using showOption function
		// connect to database and auth user
		//paging
		require_once $this->sysRoot . '/model/' . 'auth.php';
		$ob = new auth();
		$check = $ob -> check();
		$member= $ob -> member;
		$logid= $ob -> logid;
		if (isset($_POST['out'])) {
			$logout = $ob -> logout();
		}
		$row = $member;

		require_once './PHPTAL-1.3.0/PHPTAL.php';// create a new template object
		$template = new PHPTAL($this->sysRoot . '/view/' .'user_read1.html');
		$template->username = $row['username'];
		$template->id = $row['id'];
		$template->linkid="/thanh/mvc/user/edit/?id=".$row['id'];
		try {
			echo $template->execute();
		}
		catch (Exception $e){
			echo $e;
		}
	}
	public function edit()
    {
	    if ('GET'==$_SERVER['REQUEST_METHOD']&&isset($_GET['id'])){
			require $this->sysRoot . '/model/' . 'auth.php';
			$auth = new auth();
			$d = $auth -> check();
			$member= $auth -> member;
			//admin auth
			if($member['id']!=61){
				if($member['id']!=$_GET['id']){
					$_GET['id']=$member['id'];
				}
			}
			/////////////
			$id =$_GET['id'];
			require $this->sysRoot . '/model/' . 'Connection.php';
			$ob = new Member();
			list($st, $stt)= $ob -> get_update($id);
			$row=$st;
		}
		#戻る場合
		elseif('POST'==$_SERVER['REQUEST_METHOD']){
			session_start();
			$row=$_SESSION;
		}
		$sdate='';
		$stime='';
		if (isset($row)) {
			  $sdate = strtotime($row['sdate']);
			  $stime = strtotime($row['stime']);
		}else{
			$row['id']=NULL;
			$row['username']='名前を入力してください';
			$row['password']='パスワードを入力してください';
		}
		#print($id);
		#echo('<pre>');var_dump($row);echo('</pre>');
		require_once './PHPTAL-1.3.0/PHPTAL.php';// create a new template object
		$template = new PHPTAL($this->sysRoot . '/view/' .'user_edit1.html');
		$template->row = $row;
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
		$_SESSION['id']=htmlspecialchars($_POST['id']);
		if(empty($_SESSION['id'])){
			$_SESSION['id']=NULL;
		}
		#$_SESSION['username']=htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
		#$pass     = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
		#$password = "ewweg".$pass."asdfe";   ////////$passをsha1で暗号化   //前後に任意の文字列を追加
		#$passd    = hash("sha256",$password);
		#$_SESSION['password']=$passd;
		$username  = htmlspecialchars($_POST['username'],ENT_QUOTES);
		$password  = htmlspecialchars($_POST['password'],ENT_QUOTES);
		$password1 = hash("sha256",$password);
		$passw     = "ewweg".$password1."asdfe";
		$passd     = hash("sha256",$passw);

		$_SESSION['username']  =htmlspecialchars($_POST['username']);
		$_SESSION['password']  =$passd;
		$title1   = $_SESSION['username'];
		$memo1    = $_SESSION['password'];
		$sid      = $_SESSION['id'];
		require_once './PHPTAL-1.3.0/PHPTAL.php';// create a new template object
		$template = new PHPTAL($this->sysRoot . '/view/' .'user_check1.html');
		$template->title1 = $title1;
		$template->pass = '************';
		try {
			echo $template->execute();
		}
		catch (Exception $e){
			echo $e;
		}
	}
	public function update()
    {
			session_start();
	#   var_dump($_SESSION['username']);
	#   var_dump($_SESSION['password']);
	#    var_dump($_SESSION['id']);
		require $this->sysRoot . '/model/' . 'Connection.php';
		$up = new Member;
			if (isset($_POST['update'])) {
				$a = $up -> update();
			}
			elseif (isset($_POST['delete'])) {
			$a22 = $up -> del();
			}
		require $this->sysRoot . '/model/' . 'auth.php';
            $auth = new auth();
            $d = $auth -> check();
            $member= $auth -> member;
            //admin auth
            if($member['id']!=61){
           		header('Location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/login/signin');
			}else{
				header('Location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/admin/read');
			}

		#if(isset($_SESSION['admin'])){
		#var_dump($_SESSION['admin']);
		#header('Location: http://61.215.122.192/thanh/day08/admin_read.php');
		#	header('Location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/admin/read');
		#} else{
		#	header('Location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/login/signin');
		#}
	}

}

