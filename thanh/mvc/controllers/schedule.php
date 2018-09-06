
<?php
require_once "./library/sys.php";
require_once "./PHPTAL-1.3.0/PHPTAL.php";
class scheduleController extends setSys
    {

    public function read()
    {
		require_once $this->sysRoot . '/controllers/' . 'Encode.php';
		// using showOption function
		// connect to database and auth user
		//paging
		require_once $this->sysRoot . '/library/' . 'calendar.php';
		require_once $this->sysRoot . '/model/' . 'auth.php';
		$ob = new auth();
		$check = $ob -> check();
		$member= $ob -> member;
		#var_dump($member);
		$logid= $ob -> logid;
		if (isset($_POST['out'])) {
			$logout = $ob -> logout();
			unset($_SESSION['admin']);
		}

		if(isset($_GET['page'])){
			if(preg_match('/^[1-9][0-9]*$/', $_GET['page'])){
				$page =(int)$_GET['page'];
			}
		}else {
			$page =1;
		}
		require_once $this->sysRoot . '/model/' . 'schedule.php';
		$schedule = new schedule();
		list($stt, $total, $totalPages, $row1 ) = $schedule -> ichiran($page,$member);
		//big search form
		if(isset($_POST['title'])){
			$_SESSION['title']          = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
			$_SESSION['sdate_year']     = $_POST['sdate_year'];
			$_SESSION['sdate_month']    = $_POST['sdate_month'];
			$_SESSION['sdate_day']      = $_POST['sdate_day'];
			$_SESSION['stime_hour']     = $_POST['stime_hour'];
			$_SESSION['stime_minute']   = $_POST['stime_minute'];
			if($_POST['sdate_month']<10){
				$_SESSION['sdate_month']='0'.$_POST['sdate_month']; 
			}
			if($_POST['sdate_day']<10){
				$_SESSION['sdate_day']='0'.$_POST['sdate_day']; 
			}
			if($_POST['stime_hour']<10){
				$_SESSION['stime_hour']='0'.$_POST['stime_hour']; 
			}
			if($_POST['stime_minute']<10){
				$_SESSION['stime_minute']='0'.$_POST['stime_minute']; 
			}
			$sdate= $_SESSION['sdate_year'].'-'.$_SESSION['sdate_month'].'-'.$_SESSION['sdate_day'];
			$stime= $_SESSION['stime_hour'].':'.$_SESSION['stime_minute'].':00';
			$stt=NULL;
			list($stt, $total, $totalPages, $row1 ) = $schedule -> search($page,$member,$sdate,$stime,$_SESSION['title']);
			unset($_SESSION['title']);
			unset($_SESSION['sdate_year']);
			unset($_SESSION['sdate_month']);
			unset($_SESSION['sdate_day']);
			unset($_SESSION['stime_hour']);
			unset($_SESSION['stime_minute']);
		}
		//管理者であるかどうか確認
		if($logid=='exploguy'){
			$admin='ユーザー管理';
			$_SESSION['admin']=$logid;
		}else{
			$admin='';
		}
		///
		



		$row['sid']=NULL;
		$row['sdate'] = -1;
		$row['stime'] = -1;
		$row['title'] = '予定名を入力してください';
		$row['memo']  = '備考を入力してください';
		$sdate = strtotime($row['sdate']);
		$stime = strtotime($row['stime']);
		$calendar= new calendar();
		$year=$calendar->showOption(2009, 2015, date('Y', $sdate));
		$month=$calendar->showOption(1, 12, date('n', $sdate));
		$day=$calendar->showOption(1, 31, date('j', $sdate)); 
		$hour=$calendar->showOption(0, 23, date('G', $stime));
		$minute=$calendar->showOption(0, 59, date('i', $stime), 15);
		require_once './PHPTAL-1.3.0/PHPTAL.php';// create a new template object
		$template = new PHPTAL($this->sysRoot . '/view/' .'./schedule_read1.html');
		$template->years=$year;	
		$template->months=$month;
		$template->days=$day;
		$template->hours=$hour;
		$template->minutes=$minute;
		#echo('<pre>');	var_dump($year);echo('</pre>');
		//pagging
		if($page>1){
			$backpage=$page-1;
		}
		if($page=1){
			$backpage=$page;
		}
		$template->backpage="/thanh/mvc/schedule/read/?page=".$backpage;
		$pagecount=array();
		for ($i = 1; $i <= $totalPages; $i++){
			$pag[$i]['pagecount']=$i;
			$pag[$i]['pagelink']="/thanh/mvc/schedule/read/?page=".$i;
		}
		if($page<$totalPages){
			$nextpage=$page+1;
		}
		if($page=$totalPages){
			$nextpage=$page;
		}	

		$template->logid = $logid;
		$template->member = $member;
		$template->stt = $stt;
		$template->total = $total;
		$template->totalPages =$totalPages;
		$template->admin = $admin;
		$template->nextpage="/thanh/mvc/schedule/read/?page=".$nextpage;
		$template->pag=$pag;
		#echo('<pre>');var_dump($pag);echo('</pre>');
		$template->backpage="/thanh/mvc/schedule/read/?page=".$backpage;
		$temp = $stt->fetchAll();
		#echo('<pre>');var_dump($temp);echo('</pre>');
		$temp2 = array();
		foreach ($temp as $key => $value) {
			$temp2[$key]['sid'] = $temp[$key]['sid'];
			$temp2[$key]['sdate'] = $temp[$key]['sdate'];
			$temp2[$key]['stime'] = $temp[$key]['stime'];
			$temp2[$key]['title'] = $temp[$key]['title'];
			$temp2[$key]['memo'] = $temp[$key]['memo'];
			$temp2[$key]['link'] = "/thanh/mvc/schedule/edit/?sid=".$temp2[$key]['sid'];
		}
		#var_dump($temp2);
		#$temp['link']="schedule_edit.php?sid=".e(htmlspecialchars($temp['sid']));
		#echo('<pre>');var_dump($temp2);echo('</pre>');
		$template->row = $temp2;
		#var_dump($template->row);
		try {
			echo $template->execute();
		}
		catch (Exception $e){
			echo $e;
		}
	}
	public function edit()
    {	
		require_once $this->sysRoot . '/library/' . 'calendar.php';
		if ('GET'==$_SERVER['REQUEST_METHOD']&&isset($_GET['sid'])){
		// auth user
		// read
		require_once $this->sysRoot . '/model/' . 'auth.php';
		$auth = new auth();
		$d = $auth -> check();
		$member= $auth -> member;
		require_once $this->sysRoot . '/model/' . 'schedule.php';
		$schedule = new schedule();
		list($stt, $row) = $schedule -> read_update($_GET['sid'],$member['id']);
		//変数を宣言
		$kaizo='';
		$sdate='';
		$stime='';
		if(empty($row)){
			$kaizo='無効な入力';
			header('Location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/schedule/read');
			break;
		}
		#戻る場合
		}elseif('POST'==$_SERVER['REQUEST_METHOD']){
			session_start();
			$row=$_SESSION;
			$row['id']=$_SESSION['member_id'];
			#var_dump($row);
			$sdate= $_SESSION['sdate_year'].'-'.$_SESSION['sdate_month'].'-'.$_SESSION['sdate_day'];
			$stime=$_SESSION['stime_hour'].':'.$_SESSION['stime_minute'];
		}
		else{
		// connect to database and auth user
			require_once $this->sysRoot . '/model/' . 'auth.php';
			$auth = new auth();
			$d = $auth -> check();
			$member= $auth -> member;
		}

		if (isset($row)) {
			  $sdate = strtotime($row['sdate']);
			  $stime = strtotime($row['stime']);
		}else{
			$row['sid']=NULL;
			$row['sdate']=-1;
			$row['stime']=-1;
			$row['title']='';
			$row['memo']='';
			$sdate = strtotime($row['sdate']);
			$stime = strtotime($row['stime']);
		}
			//calendar------
		$calendar= new calendar();
		$year=$calendar->showOption(2009, 2015, date('Y', $sdate));
		$month=$calendar->showOption(1, 12, date('n', $sdate));
		$day=$calendar->showOption(1, 31, date('j', $sdate));
		$hour=$calendar->showOption(0, 23, date('G', $stime));
		$minute=$calendar->showOption(0, 59, date('i', $stime), 15);
		//////////////
		require_once './PHPTAL-1.3.0/PHPTAL.php';// create a new template object
		$template = new PHPTAL($this->sysRoot . '/view/' .'./schedule_edit1.html');
		$template->years=$year;
		$template->months=$month;
		$template->days=$day;
		$template->hours=$hour;
		$template->minutes=$minute;

		$template->kaizo=$kaizo;
		$template->row=$row;
		$template->member=$member;
		if(isset($member)){
			$template->member=$member;
		}else{	
		$template->member=$row;//戻るボタン
		}
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
		$_SESSION['sid']=htmlspecialchars($_POST['sid']);
		if(empty($_SESSION['sid'])){
			$_SESSION['sid']=NULL;
		}
		$_SESSION['title']       = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
		$_SESSION['sdate_year']  = $_POST['sdate_year'];
		$_SESSION['sdate_month'] = $_POST['sdate_month'];
		$_SESSION['sdate_day']   = $_POST['sdate_day'];
		$_SESSION['stime_hour']  = $_POST['stime_hour'];
		$_SESSION['stime_minute']= $_POST['stime_minute'];
		$_SESSION['memo']        = htmlspecialchars($_POST['memo'], ENT_QUOTES, 'UTF-8');
		$_SESSION['member_id']   = $_POST['member_id'];
		//フォームで表示するため
		$title1= $_SESSION['title'];
		$date1 = $_SESSION['sdate_year'].'/'.$_SESSION['sdate_month'].'/'.$_SESSION['sdate_day'];
		$time1 = $_SESSION['stime_hour'].':'.$_SESSION['stime_minute'];
		$memo1 = $_SESSION['memo'];
		$sid   = $_SESSION['sid'];
		require_once './PHPTAL-1.3.0/PHPTAL.php';// create a new template object
		$template = new PHPTAL($this->sysRoot . '/view/' .'./schedule_check1.html');
		$template->title1=$title1;
		$template->date1=$date1;
		$template->time1=$time1;
		$template->memo1=$memo1;
		$template->member['id']=$_SESSION['member_id'];
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
		try {
			require_once $this->sysRoot . '/model/' . 'schedule.php';
			$ob = new schedule();
			if (isset($_POST['update'])) {
				if(empty($_SESSION['sid'])){//insert into schedule of database
					list($stt, $row) = $ob -> add();
				}else{
					$upd = $ob -> update();//updating database
				}
			} elseif (isset($_POST['delete'])) {//deleting a record
				$del = $ob -> del($_SESSION['sid']);
			}
			} catch(PDOException $e) {
				echo $e->getMessage();
				die('エラーメッセージ：'.$e->getMessage());
			}
		header('Location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/schedule/read');
	}



}


