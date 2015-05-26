<?php
class M_user extends CI_Model {
	public $default_pass;
	public $c2bi_prof;

    function __construct() {
        parent::__construct();
        $this->default_pass = "telkom";
		$this->c2bi_prof = 3;
    }
    
    public function getLists($cond="") {
	    $result = array();
		$sql = "SELECT * FROM APP_USERS";
		if($cond!='') $sql .= " WHERE ".$cond;
		$sql .= " ORDER BY NIK";
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) $result = $q->result();
		return $result;
    }
    
    public function insert($nik, $user_name, $email, $loker, $addr_street, $addr_city, $contact_no) {
	    $new_id = $this->getNewUserId();
	    $sql = "INSERT INTO APP_USERS(USER_ID, NIK, USER_NAME, EMAIL, LOKER, ADDR_STREET, ADDR_CITY, CONTACT_NO) ".
	    	"VALUES(".$new_id.", '".$nik."', '".$user_name."', '".$email."', '".$loker."', '".$addr_street."', '".$addr_city."', '".$contact_no."') ";
		$q = $this->db->query($sql); 
		return $new_id;
    }
    
    public function update($user_id, $nik, $user_name, $email, $loker, $addr_street, $addr_city, $contact_no) {
	    $sql = "UPDATE APP_USERS SET NIK='".$nik."', USER_NAME='".$user_name."', EMAIL='".$email."', LOKER='".$loker.
	    	"', ADDR_STREET='".$addr_street."', ADDR_CITY='".$addr_city."', CONTACT_NO='".$contact_no."' WHERE USER_ID=".$user_id;
		$q = $this->db->query($sql); 
    }
    
    public function remove($user_id) {
	    $sql = "DELETE FROM APP_USERS WHERE USER_ID=".$user_id;
		$q = $this->db->query($sql);
		
		$sql = "DELETE FROM APP_USER_PROFILE WHERE USER_ID=".$user_id;
		$q = $this->db->query($sql);
		
		$sql = "DELETE FROM APP_USER_C2BI WHERE USER_ID=".$user_id;
		$q = $this->db->query($sql);
    }
    
    public function clearProfile($user_id) {
	    $sql = "DELETE FROM APP_USER_PROFILE WHERE USER_ID=".$user_id;
		$q = $this->db->query($sql);
    }
    
    public function setProfile($user_id, $prof_id) {
	    $sql = "INSERT INTO APP_USER_PROFILE(USER_ID, PROF_ID) VALUES(".$user_id.", ".$prof_id.")";
		$q = $this->db->query($sql);
    }
    
    public function getProfile($user_id) {
	    $result = array();
	    $sql = "SELECT PROF_ID FROM APP_USER_PROFILE WHERE USER_ID=".$user_id;
		$q = $this->db->query($sql);
		if($q->num_rows() > 0) {
			foreach($q->result() as $k=>$v)
				array_push($result, $v->PROF_ID);
		}
		return $result;
    }
	
	public function getUserProfile($user_id) {
	    $result = array();
	    $sql = "SELECT A.PROF_ID, B.PROF_NAME FROM APP_USER_PROFILE A, APP_PROFILE B ".
			"WHERE A.PROF_ID=B.PROF_ID AND A.USER_ID=".$user_id;
		$q = $this->db->query($sql);
		if($q->num_rows() > 0) {
			foreach($q->result() as $k=>$v)
				$result[$v->PROF_ID] = $v->PROF_NAME;
		}
		return $result;
    }
    
    public function setPassword($user_id, $passwd) {
	    $sql = "UPDATE APP_USERS SET PASSWD='".md5($passwd)."' WHERE USER_ID=".$user_id;
		$q = $this->db->query($sql);
    }
    
    public function setPasswordDefault($user_id) {
	    $this->setPassword($user_id, $this->default_pass);
    }
    
    private function getNewUserId() {
		$q = $this->db->query("SELECT MAX(USER_ID)+1 N FROM APP_USERS");
		foreach($q->result() as $r) $n = $r->N;
		if ($n=='' || $n=='0') $n=1;
		return $n;
	}
	
	// ========== C2Bi :: BOL ========== //
	public function getC2BiLists($cond="") {
	    $result = array();
		$sql = "SELECT A.* FROM APP_USERS A, APP_USER_PROFILE B WHERE A.USER_ID=B.USER_ID AND B.PROF_ID=".$this->c2bi_prof;
		if($cond!='') $sql .= " AND ".$cond;
		$sql .= " ORDER BY A.NIK";
		$q = $this->db->query($sql); 
		//echo $sql;
		if($q->num_rows() > 0) $result = $q->result();
		return $result;
    }
	
	public function clearProfilePgl($user_id) {
	    $sql = "DELETE FROM APP_USER_C2BI WHERE USER_ID=".$user_id;
		$q = $this->db->query($sql);
    }
	
	public function removeProfilePgl($user_id, $pgl_id) {
	    $sql = "DELETE FROM APP_USER_C2BI WHERE USER_ID=".$user_id." AND PGL_ID=".$pgl_id;
		$q = $this->db->query($sql);
    }
    
    public function setProfilePgl($user_id, $pgl_id) {
	    $sql = "INSERT INTO APP_USER_C2BI(USER_ID, PGL_ID) VALUES(".$user_id.", ".$pgl_id.")";
		$q = $this->db->query($sql);
    }
    
    public function getPgl($user_id, $cond="") {
	    $result = array();
	    $sql = "SELECT B.* FROM APP_USER_C2BI A, CUST_PGL B WHERE A.PGL_ID=B.PGL_ID AND A.USER_ID=".$user_id;
		if($cond!='') $sql .= " AND ".$cond;
		$sql .= " ORDER BY B.PGL_NAME";
		$q = $this->db->query($sql);
		if($q->num_rows() > 0) {
			$result = $q->result();
		}
		return $result;
    }
    // ========== C2Bi :: EOL ========== //
    
    // ========== Authentication & Authorization :: BOL ========== //
    public function isExists($member_id, $member_pass, &$user_ref) {
	    /*$user = $this->getLists("member_id='".$member_id."' and member_pass='".md5($member_pass)."'");
	    if(count($user) > 0) return TRUE;
	    return FALSE;*/
	    $result = FALSE;
	    $user = $this->getLists("(email='".$member_id."' or email='".$member_id."@telkomsel.co.id') and member_pass='".$member_pass."'");
	    if(count($user) == 1) {
		    $sql = "update members set login_num=login_num+1 where (email='".$member_id."' or email='".$member_id."@telkomsel.co.id') and member_pass='".$member_pass."'";
			$q = $this->db->query($sql);
		    $result = TRUE;
	    }
	    /*else {
		    $user = $this->getLists("member_id='".$member_id."' and member_pass='".$member_pass."'");
		    if(count($user) == 1) $result = TRUE;
	    }*/
	    $user_ref = $user;
	    return $result;
    }
    
    // ========== Authentication & Authorization :: EOL ========== //
    
}
?>