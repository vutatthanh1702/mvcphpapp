<?php
require_once "construct.php";
class schedule extends construct
 {

    private $link;
    public $member;
    public $logid;
	public $total;
	public $totalPages;
	public $offset;
    public  $db;
    public function ichiran($page,$member) {
        $offset=COMMENTS_PER_PAGE * ($page -1);
        $sql ="SELECT * FROM schedule WHERE member_id = ".$member['id'];
		$sql.=" ORDER BY sdate DESC, stime DESC LIMIT ".$offset.",".COMMENTS_PER_PAGE;
		$stt = $this->db->prepare($sql);
        $total =$this->db->query("select count(*) from schedule WHERE member_id = ".$member['id'])->fetchColumn();
        $totalPages = ceil($total /COMMENTS_PER_PAGE);
        $stt->execute();
		$row1 = $stt->fetch(PDO::FETCH_ASSOC);
        return array($stt, $total, $totalPages, $row1 );
    }
	public function search($page,$member,$sdate,$stime,$title) { 
		$offset=COMMENTS_PER_PAGE * ($page -1);
        $sql ="SELECT * FROM schedule WHERE member_id = ".$member['id']." GROUP BY sid HAVING sdate like '%".$sdate;
        $sql.="%' AND stime like '%".$stime."%' OR title like '%".$title;
        $sql.="%' ORDER BY sdate DESC, stime DESC LIMIT ".$offset.",".COMMENTS_PER_PAGE;
		$stt = $this->db->prepare($sql);
        $total =$this->db->query("select count(*) from schedule WHERE member_id = ".$member['id'])->fetchColumn();
        $totalPages = ceil($total /COMMENTS_PER_PAGE);
		$stt->execute();
        $row1 = $stt->fetch(PDO::FETCH_ASSOC);
        return array($stt, $total, $totalPages, $row1 );
    }
	public function read_update($sid, $member_id) {
        $stt = $this->db->prepare("SELECT * FROM schedule WHERE sid = :sid and member_id = :member_id");
	    $stt->bindValue(':sid', $sid);
		$stt->bindValue(':member_id', $member_id);
  	    $stt->execute();
    	$row = $stt->fetch(PDO::FETCH_BOTH);
        return array($stt, $row);
    }
	public function add() {
		$stt = $this->db->prepare("INSERT INTO schedule(member_id, title, sdate, stime, memo) VALUES(:member_id,:title, :sdate, :stime, :memo)");
      	$stt->bindValue(':member_id', $_SESSION['member_id'], PDO::PARAM_STR);
      	$stt->bindValue(':title', $_SESSION['title'], PDO::PARAM_STR);
      	$stt->bindValue(':sdate', $_SESSION['sdate_year'].'/'.$_SESSION['sdate_month'].'/'.$_SESSION['sdate_day'], PDO::PARAM_STR);
      	$stt->bindValue(':stime', $_SESSION['stime_hour'].':'.$_SESSION['stime_minute'], PDO::PARAM_STR);
      	$stt->bindValue(':memo', $_SESSION['memo'], PDO::PARAM_STR);
		$stt->execute();
        $db = NULL;
      	unset($_SESSION['title']);
      	unset($_SESSION['sdate_year']);
      	unset($_SESSION['sdate_month']);
      	unset($_SESSION['sdate_day']);
      	unset($_SESSION['stime_hour']);
      	unset($_SESSION['stime_minute']);
      	unset($_SESSION['memo']);
		$row = $stt->fetch(PDO::FETCH_BOTH);
		return array($stt, $row);
	}
	public function update() { 
		$stt = $this->db->prepare('UPDATE schedule SET title=:title, sdate=:sdate, stime=:stime, memo=:memo WHERE sid=:sid');
		$stt->bindValue(':title',$_SESSION['title']);
		$stt->bindValue(':sdate', $_SESSION['sdate_year'].'/'.$_SESSION['sdate_month'].'/'.$_SESSION['sdate_day']);
		$stt->bindValue(':stime', $_SESSION['stime_hour'].':'.$_SESSION['stime_minute']);
		$stt->bindValue(':memo', $_SESSION['memo']);
		$stt->bindValue(':sid', $_SESSION['sid']);
		$stt->execute();
		unset($_SESSION['title']);
		unset($_SESSION['sdate_year']);
		unset($_SESSION['sdate_month']);
		unset($_SESSION['sdate_day']);
		unset($_SESSION['stime_hour']);
		unset($_SESSION['stime_minute']);
		unset($_SESSION['memo']);
		unset($_SESSION['sid']);
		return $stt;
	}
	public function del($sid) {
		$stt = $this->db->prepare('DELETE FROM schedule WHERE sid=:sid');
        $stt->bindValue(':sid', $_SESSION['sid']);
        $stt->execute();
        unset($_SESSION['sid']);
        return $stt;
    }
}


