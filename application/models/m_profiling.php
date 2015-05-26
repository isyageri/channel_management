<?php
class M_profiling extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    
    // ========== Profile :: BOL ========== //
    public function getLists($cond="") {
	    $result = array();
		$sql = "SELECT * FROM APP_PROFILE ";
		if($cond!='') $sql .= " WHERE ".$cond;
		$sql .= " ORDER BY PROF_ID";
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) $result = $q->result();
		return $result;
    }
    
    public function insert($prof_name, $prof_desc) {
	    $new_id = $this->getNewProfId();
	    $sql = "INSERT INTO APP_PROFILE(PROF_ID, PROF_NAME, PROF_DESC) VALUES(".$new_id.", '".$prof_name."', '".$prof_desc."')";
		$q = $this->db->query($sql); 
    }
    
    public function update($prof_id, $prof_name, $prof_desc) {
	    $sql = "UPDATE APP_PROFILE SET PROF_NAME='".$prof_name."', PROF_DESC='".$prof_desc."' WHERE PROF_ID=".$prof_id;
		$q = $this->db->query($sql); 
    }
    
    public function remove($prof_id) {
	    $sql = "DELETE FROM APP_PROFILE WHERE PROF_ID=".$prof_id;
		$q = $this->db->query($sql); 
		
		$sql = "DELETE FROM APP_MENU_PROFILE WHERE PROF_ID=".$prof_id;
		$q = $this->db->query($sql); 
		
		$sql = "DELETE FROM APP_USER_PROFILE WHERE PROF_ID=".$prof_id;
		$q = $this->db->query($sql); 
    }
    
    private function getNewProfId() {
		$q = $this->db->query("SELECT MAX(PROF_ID)+1 N FROM APP_PROFILE");
		foreach($q->result() as $r) $n = $r->N;
		if ($n=='' || $n=='0') $n=1;
		return $n;
	}
	
	public function pagination() {
		echo "<div id='pager'><form>";
	
		echo "<img src='".image_asset_url("first.png")."' class='first'/>";
		echo "<img src='".image_asset_url("prev.png")."' class='prev'/>";
		echo "<input type='text' class='pagedisplay' size='7'/>";
		echo "<img src='".image_asset_url("next.png")."' class='next'/>";
		echo "<img src='".image_asset_url("last.png")."' class='last'/>";
		echo "<select class='pagesize'>";
		echo "	<option value='20'>20</option>";
		echo "	<option value='40'>40</option>";
		echo "	<option value='50'>50</option>";
		echo "</select>";
		echo "</form></div>";
	}
    
    // ========== Profile :: EOL ========== //
    
    
    // ========== Menu :: BOL ========== //
    public function getMenuAll($cond="") {
	    $result = array();
		$sql = "SELECT * FROM APP_MENU ";
		if($cond!='') $sql .= " WHERE ".$cond;
		$sql .= " ORDER BY MENU_ID";
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) $result = $q->result();
		return $result;
    }
	
	public function getMenuByProf($prof_id, $menu_parent) {
	    $result = array();
		$sql = "SELECT A.* FROM APP_MENU A, APP_MENU_PROFILE B WHERE A.MENU_ID=B.MENU_ID AND B.PROF_ID=".
			$prof_id." AND A.MENU_PARENT=".$menu_parent;
		$sql .= " ORDER BY A.MENU_ID";
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) $result = $q->result();
		return $result;
    }
	
	public function getBreadcrumb($menu_id) {
		$str = "";
		if($menu_id>0) {
			$tmp = $this->getMenuAll("MENU_ID=".$menu_id);
			$str = $tmp[0]->MENU_NAME;
			if($tmp[0]->MENU_PARENT > 0) {
				$str = $this->getBreadcrumb($tmp[0]->MENU_PARENT)." > ".$str;
			}
		}
		return $str;
	}
    
    public function getMenuAssigned($prof_id, $cond="") {
	    $result = array();
		$sql = "select a.* from menu a, menu_profile b where a.menu_id=b.menu_id and b.prof_id=".$prof_id;
		if($cond!='') $sql .= " and ".$cond;
		$sql .= " order by a.menu_id";
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) $result = $q->result();
		return $result;
    }
    
    public function menuInsert($menu_name, $menu_icon, $menu_desc, $menu_link) {
	    $new_id = $this->getNewMenuId();
	    $sql = "INSERT INTO APP_MENU(MENU_ID, MENU_NAME, MENU_ICON, MENU_DESC, MENU_LINK) VALUES(".$new_id.
	    	", '".$menu_name."', '".$menu_icon."', '".$menu_desc."', '".$menu_link."')";
		$q = $this->db->query($sql); 
		return $new_id;
    }
    
    public function menuUpdate($menu_id, $menu_name, $menu_icon, $menu_desc, $menu_link) {
	    $sql = "UPDATE APP_MENU SET MENU_NAME='".$menu_name."', MENU_ICON='".$menu_icon.
	    	"', MENU_DESC='".$menu_desc."', MENU_LINK='".$menu_link."' WHERE MENU_ID=".$menu_id;
		$q = $this->db->query($sql); 
    }
    
    public function menuRemove($menu_id) {
	    $sql = "DELETE FROM APP_MENU WHERE MENU_ID=".$menu_id;
		$q = $this->db->query($sql); 
		
		$sql = "DELETE FROM APP_MENU_PROFILE WHERE MENU_ID=".$menu_id;
		$q = $this->db->query($sql); 
    }
    
    public function setMenuParent($menu_id, $menu_parent) {
	    $sql = "UPDATE APP_MENU SET MENU_PARENT=".$menu_parent." WHERE MENU_ID=".$menu_id;
		$q = $this->db->query($sql); 
    }
	
	public function getAssignMenu($menu_id, $prof_id) {
		$result = FALSE;
	    $sql = "SELECT * FROM APP_MENU_PROFILE WHERE MENU_ID=".$menu_id." AND PROF_ID=".$prof_id;
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) $result = TRUE;
		return $result;
    }
    
    public function assignMenu($menu_id, $prof_id) {
	    $sql = "INSERT INTO APP_MENU_PROFILE(MENU_ID, PROF_ID) VALUES(".$menu_id.", ".$prof_id.")";
		$q = $this->db->query($sql); 
    }
    
    public function assignMenuToAll($prof_id) {
	    $sql = "INSERT INTO APP_MENU_PROFILE(MENU_ID, PROF_ID) SELECT MENU_ID, ".$prof_id." FROM APP_MENU";
		$q = $this->db->query($sql); 
	    
    }
    
    public function unassignMenu($menu_id, $prof_id) {
	    $sql = "DELETE FROM APP_MENU_PROFILE WHERE MENU_ID=".$menu_id." AND PROF_ID=".$prof_id;
		$q = $this->db->query($sql); 
    }
    
    public function unassignMenuToAll($prof_id) {
	    $sql = "DELETE FROM APP_MENU_PROFILE WHERE PROF_ID=".$prof_id;
		$q = $this->db->query($sql); 
    }
    
    private function getNewMenuId() {
		$q = $this->db->query("SELECT MAX(MENU_ID)+1 N FROM APP_MENU");
		foreach($q->result() as $r) $n = $r->N;
		if ($n=='' || $n=='0') $n=1;
		return $n;
	}
    
    // ========== Menu :: EOL ========== //
    
    
    // ========== Launcher :: BOL ========== //
    
    public function getLauncher($token) {
	    $result = array();
		$sql = "select token, member_id, member_pass, url, is_exec from launcher where token='".$token."'";
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) $result = $q->result();
		return $result;
	}
    
    public function insertLauncher($token, $member_id, $member_pass, $url) {
		$sql = "insert into launcher(token, member_id, member_pass, url, is_exec) values('".
			$token."', '".$member_id."', '".$member_pass."', '".$url."', 0)";
		$q = $this->db->query($sql); 
	}
	
	public function execLauncher($token) {
		$sql = "update launcher set is_exec=1 where token='".$token."'";
		$q = $this->db->query($sql); 
	}
	
	// ========== Launcher :: EOL ========== //
	
}
?>