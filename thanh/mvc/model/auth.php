<?php
require_once "construct.php";
class auth extends construct
{

    private $link;
    public $member;
    public $logid;
    public  $db;

    public function login($username,$passd) {
 	
		$sql = 'SELECT * FROM members WHERE username= :logid AND password= :logpass;';
        $stt = $this->db->prepare($sql);
        $stt -> bindParam(':logid', $username, PDO::PARAM_STR);
        $stt -> bindParam(':logpass', $passd, PDO::PARAM_STR);
        $stt -> execute();
		if ($table = $stt -> fetch(PDO::FETCH_ASSOC)) {
			$_SESSION['username'] = $table['username'];
            $_SESSION['time'] = time();
            header('Location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/schedule/read');
        }else{
            $error = '<p class="error">ＩＤまたはパスワードが正しくありません</p>';
        }
        return $stt;
    }

    public function check() {

	//Check_____________________________
		session_start();
		$logid = $_SESSION['username'];
		if (isset($_SESSION['username']) && $_SESSION['time'] + 900 > time()) {
		$_SESSION['time'] = time();

		$sql = 'SELECT * FROM members WHERE username= :logid;';
		$stt = $this->db->prepare($sql);
		$stt -> bindParam(':logid', $logid, PDO::PARAM_STR);
		$stt -> execute();
		$this->member = $stt -> fetch(PDO::FETCH_ASSOC);
		$this->logid =$_SESSION['username'];
		}else{
		header('Location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/login/signin');
		}
    }
    public function logout() {

        //Logout_____________________________
		$_SESSION = array();
		if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_id(), '', time() - 3600,
		$params["path"], $params["domain"],
		$params["secure"], $params["httponly"]
		);
		}
		session_destroy();
		setcookie('username', '', time()-3600);
		setcookie('password', '', time()-3600);
		header('Location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/login/signin');
    }

}


