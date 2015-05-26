<?php

class M_log extends CI_Model {

	function __construct() {
        parent::__construct();
    }
	
	function viewlog($user_id='', $cond='') {
		$sql = "SELECT USER_ID, AGENT, IP_ADDR, TO_CHAR(ACT_TIME, 'DD/MM/YYYY HH24:MI:SS') ACT_TIME, ACT_DESC, LONG_TIME FROM LOG";
		if($user_id!='' || $cond!='') $sql .= " WHERE 1=1 ";
		if($user_id!='') $sql .= " AND USER_ID='".$user_id."'";
		if($cond!='') $sql .= " AND ".$cond;
		$sql .= " ORDER BY ACT_TIME DESC";
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) {
			return $q->result();
		}
	}
	
	function insert($user_id, $act_desc, $long_time) {
		$sql = "INSERT INTO LOG(USER_ID, AGENT, IP_ADDR, ACT_TIME, ACT_DESC, LONG_TIME) VALUES('".$user_id."', '".$_SERVER['HTTP_USER_AGENT']."', '".$_SERVER['REMOTE_ADDR']."', SYSDATE, '".$act_desc."', ".$long_time.")";
		$q = $this->db->query($sql); 
	}

}

?>