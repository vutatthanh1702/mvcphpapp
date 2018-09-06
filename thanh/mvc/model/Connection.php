<?php
require_once "construct.php";
class Member extends construct
 {
    private $link;
	public  $db;
	public function get_list($page) {
		$offset=COMMENTS_PER_PAGE * ($page -1);
		$sql ="SELECT * FROM members ORDER BY id DESC LIMIT ".$offset.",".COMMENTS_PER_PAGE;
		$stt = $this->db->prepare($sql);
		$total =$this->db->query("select count(*) from members")->fetchColumn();
		$totalPages = ceil($total /COMMENTS_PER_PAGE);
		$stt->execute();
        return array($stt, $total, $totalPages);
    }
	public function search($page) {
        $offset=COMMENTS_PER_PAGE * ($page -1);
        $sql ="SELECT * FROM members GROUP BY id HAVING username like '%".$_SESSION['username']."%' ORDER BY id DESC LIMIT ".$offset.",".COMMENTS_PER_PAGE;
		$stt = $this->db->prepare($sql);
        $total =$this->db->query("select count(*) from members")->fetchColumn();
        $totalPages = ceil($total /COMMENTS_PER_PAGE);
        $stt->execute();
		unset($_SESSION['username']);
        return array($stt, $total, $totalPages);
    }
    public function register() {
		$stt = $this->db->prepare("INSERT INTO members(id, username, password) VALUES('',:username, :password )");
		$stt->bindValue(':username', $_SESSION['username']);
		$stt->bindValue(':password', $_SESSION['password']);
		$stt->execute();
		unset($_SESSION['username']);
        unset($_SESSION['password']);
        return $stt;
    }
	public function update() {
        $stt = $this->db->prepare('UPDATE members SET username=:username, password=:password WHERE id=:id');
		$stt->bindValue(':username',$_SESSION['username']);
		$stt->bindValue(':password', $_SESSION['password']);
		$stt->bindValue(':id', $_SESSION['id']);
        $stt->execute();
		unset($_SESSION['username']);
        unset($_SESSION['password']);
		unset($_SESSION['id']);
        return $stt;
	}
	public function del() {
        $stt = $this->db->prepare('DELETE FROM members WHERE id=:id');
        $stt->bindValue(':id', $_SESSION['id']);
        $stt->execute();
        if (!$stt) die('Invalid query: ' . mysql_error());
		unset($_SESSION['id']);
        return $stt;
    }
	public function get_update($id) {
        $stt = $this->db->prepare('SELECT * FROM members WHERE id=:id');
        $stt->bindValue(':id', $id);
        $stt->execute();
		$st=$stt -> fetch(PDO::FETCH_ASSOC);
    	return array($st, $stt);
		unset($_SESSION['id']);
	}

}

