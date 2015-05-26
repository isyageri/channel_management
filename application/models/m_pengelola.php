<?php
class M_pengelola extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    public function getLists($cond="") {
	    $result = array();
		$sql = "SELECT * FROM (SELECT A.PGL_ID, A.PGL_NAME, A.PGL_ADDR, A.PGL_CONTACT_NO, A.ENABLE_FEE, COUNT(DISTINCT B.TEN_ID) JML_TEN, COUNT(DISTINCT C.ND) JML_ND ".
			" FROM CUST_PGL A, PGL_TEN B, TEN_ND C WHERE A.PGL_ID=B.PGL_ID(+) AND B.TEN_ID=C.TEN_ID(+) ".
			" GROUP BY A.PGL_ID, A.PGL_NAME, A.PGL_ADDR, A.PGL_CONTACT_NO, A.ENABLE_FEE)";
		if($cond!='') $sql .= " WHERE ".$cond;
		$sql .= " ORDER BY PGL_NAME";
		$q = $this->db->query($sql); 
		//echo $sql;
		if($q->num_rows() > 0) $result = $q->result();
		return $result;
    }
    
    public function insert($pgl_id, $pgl_name, $pgl_addr, $pgl_contact_no, $enable_fee) {
		$new_id = $this->getNewPglId();
	    $sql = "INSERT INTO CUST_PGL(PGL_ID, PGL_NAME, PGL_ADDR, PGL_CONTACT_NO, ENABLE_FEE) ".
	    	"VALUES(".$new_id.", '".$pgl_name."', '".$pgl_addr."', '".$pgl_contact_no."', '".$enable_fee."') ";
		$q = $this->db->query($sql); 
    }
    
    public function update($pgl_id, $pgl_name, $pgl_addr, $pgl_contact_no, $enable_fee) {
	    $sql = "UPDATE CUST_PGL SET PGL_NAME='".$pgl_name."', PGL_ADDR='".$pgl_addr."', PGL_CONTACT_NO='".
	    	$pgl_contact_no."', ENABLE_FEE='".$enable_fee."' WHERE PGL_ID=".$pgl_id;
		$q = $this->db->query($sql); 
    }
    
    public function remove($pgl_id) {
	    $sql = "DELETE FROM CUST_PGL WHERE PGL_ID=".$pgl_id;
		$q = $this->db->query($sql);
		$sql = "DELETE FROM PGL_TEN WHERE PGL_ID=".$pgl_id;
		$q = $this->db->query($sql);
		
		$sql = "SELECT * FROM NPK WHERE PGL_ID=".$pgl_id;
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) {
			foreach($q->result() as $k => $r) {
				$sql = "DELETE FROM NPK_PROCESS WHERE NPK_ID=".$r->NPK_ID;
				$qdel = $this->db->query($sql); 
				$sql = "DELETE FROM NPK_TABLE WHERE NPK_ID=".$r->NPK_ID;
				$qdel = $this->db->query($sql);
				$sql = "DELETE FROM NPK WHERE NPK_ID=".$r->NPK_ID;
				$qdel = $this->db->query($sql); 
			}
		}
    }
	
	public function removeTenant($pgl_id, $ten_id) {
		$sql = "DELETE FROM PGL_TEN WHERE PGL_ID=".$pgl_id." AND TEN_ID=".$ten_id;
		$q = $this->db->query($sql);
    }

	private function getNewPglId() {
		$q = $this->db->query("SELECT MAX(PGL_ID)+1 N FROM CUST_PGL");
		foreach($q->result() as $r) $n = $r->N;
		if ($n=='' || $n=='0') $n=1;
		return $n;
	}
    
    
}
?>