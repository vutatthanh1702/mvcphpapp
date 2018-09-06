<?php
class construct
{
public $host='mysql:host=localhost;dbname=php10';
public $username='phpusr';
public $password='phppass';
public function __construct(){
        //$this->username    = $username;
        define('COMMENTS_PER_PAGE', 10);
        //$this->password    = $password;
        try {
            $dbh = new PDO($this->host, $this->username, $this->password);
            $this->db=$dbh;
            $this->db->exec('SET NAMES utf8');
            //foreach($dbh->query('SELECT * from members') as $row) {
            //  print_r($row);
            //}
            //$dbh = null;
        } catch (PDOException $e) {
            print "エラー!: " . $e->getMessage() . "<br/>";
            die();
        }
		#define('COMMENTS_PER_PAGE', 10);
        return true;
    }
}
