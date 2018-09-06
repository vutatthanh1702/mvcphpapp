<?php
require_once "./library/sys.php";
class adminController extends setSys
    { 
    public function read()
    {
		require_once $this->sysRoot . '/controllers/' . 'Encode.php';
		try {
			  $db = new PDO('mysql:host=localhost; dbname=php10', 'phpusr', 'phppass');
			  $db->exec('SET NAMES utf8');
		} catch(PDOException $e) {
			  die('エラーメッセージ：'.$e->getMessage());
		}
		//pagging
		$page=1;
		if(isset($_GET['page'])){
			if(preg_match('/^[1-9][0-9]*$/', $_GET['page'])){
				$page =(int)$_GET['page'];

			}else {
				$page =1;
			}
		}
		////////////
		require_once $this->sysRoot . '/model/' . 'Connection.php';
		$schedule = new Member();
		list($stt, $total, $totalPages ) = $schedule -> get_list($page);
		if(isset($_POST['username'])){
			session_start();
			$_SESSION['username']=htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
			list($stt, $total, $totalPages ) = $schedule -> search($page);
		}

		$row['id']      =NULL;
		$row['username']='名前を入力してください';
		$row['password']='パスワードを入力してください';
		$temp = $stt->fetchAll();
		#echo('<pre>');var_dump($temp);echo('</pre>');
		$temp2 = array(); 
		foreach ($temp as $key => $value) {
			$temp2[$key]['id'] = $temp[$key]['id'];
			$temp2[$key]['username'] = $temp[$key]['username'];
			$temp2[$key]['password'] = $temp[$key]['password']; 
			$temp2[$key]['link'] = "/thanh/mvc/user/edit/?id=".$temp2[$key]['id'];
		}
		
		require_once './PHPTAL-1.3.0/PHPTAL.php';// create a new template object
		$template = new PHPTAL($this->sysRoot . '/view/' .'admin_read1.html');
		$template->stt = $stt;
		$template->row = $temp2;
		
		
		//pagging
		if($page>1){ 
			$backpage=$page-1;
		}
		if($page=1){
			$backpage=$page;
		}
		$template->backpage="/thanh/mvc/admin/read/?page=".$backpage;
		$pagecount=array();
		for ($i = 1; $i <= $totalPages; $i++){
			$pag[$i]['pagecount']=$i;
			$pag[$i]['pagelink']="/thanh/mvc/admin/read/?page=".$i;
		}
		if($page<$totalPages){
			$nextpage=$page+1;
		}
		if($page=$totalPages){
			$nextpage=$page;
		}
		$template->nextpage="/thanh/mvc/admin/read/?page=".$nextpage;
		$template->pag=$pag;
		#echo('<pre>');var_dump($pag);echo('</pre>');
		$template->backpage="/thanh/mvc/admin/read/?page=".$backpage;
		$template->total = $total;
		$template->totalPages=$totalPages;
		///////

		try {
			echo $template->execute();
		}
		catch (Exception $e){
			echo $e;
		}
	}
}

