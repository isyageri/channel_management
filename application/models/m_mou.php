<?php
class M_mou extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
	// MOU
    public function getLists($cond="") {
	    $result = array();
		$sql = "SELECT MOU_NO, A.PGL_ID, TO_CHAR(START_DATE, 'DD/MM/YYYY') START_DATE, TO_CHAR(END_DATE, 'DD/MM/YYYY') END_DATE, ".
			"PGL_NAME FROM MOU A, CUST_PGL B WHERE A.PGL_ID=B.PGL_ID ";
		if($cond!='') $sql .= " AND ".$cond;
		$sql .= " ORDER BY START_DATE DESC";
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) $result = $q->result();
		return $result;
    }

    public function insert($mou_no, $pgl_id, $start_date, $end_date) {
	    $sql = "INSERT INTO MOU(MOU_NO, PGL_ID, START_DATE, END_DATE) VALUES('".$mou_no."', ".$pgl_id.", ".
	    	"TO_DATE('".$start_date."', 'DD/MM/YYYY'), TO_DATE('".$end_date."', 'DD/MM/YYYY')) ";
		$q = $this->db->query($sql); 
    }
    
    public function update($mou_no, $pgl_id, $start_date, $end_date) {
	     $sql = "UPDATE MOU SET PGL_ID=".$pgl_id.", START_DATE=TO_DATE('".$start_date."', 'DD/MM/YYYY'), ".
			"END_DATE=TO_DATE('".$end_date."', 'DD/MM/YYYY') WHERE MOU_NO='".$mou_no."'";
		$q = $this->db->query($sql); 
    }
    
    public function remove($mou_no) {
	    $sql = "DELETE FROM MOU WHERE MOU_NO='".$mou_no."'";
		$q = $this->db->query($sql);
		$sql = "DELETE FROM MOU_AMD WHERE MOU_NO='".$mou_no."'";
		$q = $this->db->query($sql);
    }
	
	// Amandemen
    public function getAmdLists($cond="") {
	    $result = array();
		$sql = "SELECT AMD_NO, MOU_NO, TO_CHAR(AMD_DATE, 'DD/MM/YYYY') AMD_DATE, AMD_DESC FROM MOU_AMD ";
		if($cond!='') $sql .= " WHERE ".$cond;
		$sql .= " ORDER BY AMD_DATE DESC";
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) $result = $q->result();
		return $result;
    }

    public function insertAmd($amd_no, $mou_no, $amd_date, $amd_desc) {
	    $sql = "INSERT INTO MOU_AMD(AMD_NO, MOU_NO, AMD_DATE, AMD_DESC) VALUES('".$amd_no."', '".$mou_no."', ".
	    	"TO_DATE('".$amd_date."', 'DD/MM/YYYY'), '".$amd_desc."') ";
		$q = $this->db->query($sql); 
    }
    
    public function updateAmd($amd_no, $mou_no, $amd_date, $amd_desc) {
	    $sql = "UPDATE MOU_AMD SET MOU_NO='".$mou_no."', AMD_DATE=TO_DATE('".$amd_date."', 'DD/MM/YYYY'), ".
			"AMD_DESC='".$amd_desc."' WHERE AMD_NO='".$amd_no."'";
		$q = $this->db->query($sql); 
    }
    
    public function removeAmd($amd_no) {
		$sql = "DELETE FROM MOU_AMD WHERE AMD_NO='".$amd_no."'";
		$q = $this->db->query($sql);
    }
    
}
?>