<?php
class M_c2bi extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
	public function getPeriode($cond) {
		$result = array();
		$sql = "select substr(tname,10,6) periode from tab";
		if($cond!='') $sql .= " WHERE ".$cond;
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) $result = $q->result();
		return $result;
    }
	
	public function getRinta($period, $pgl_id, $ten_id="") {
		$result = array();
		//$sql = "SELECT * FROM TAB WHERE TNAME='CUST_RINTA_".$period."'";
		//$q = $this->db->query($sql); 
		//if($q->num_rows() > 0) {
			if($ten_id=="") {
				$sql = "SELECT b.nd nd1,A.* ".
					" FROM CUST_RINTA PARTITION(PERIOD_".$period.") A, TEN_ND B, PGL_TEN C ".
					" WHERE C.TEN_ID(+)=B.TEN_ID AND B.ND(+)=A.ND AND C.PGL_ID=".$pgl_id;
			} else {
				$sql = "SELECT b.nd nd1,A.* ".
					" FROM CUST_RINTA PARTITION(PERIOD_".$period.") A, TEN_ND B WHERE A.ND(+)=B.ND AND B.TEN_ID=".$ten_id;
			}
			$qs = $this->db->query($sql); 
			//echo $sql;
			if($qs->num_rows() > 0) $result = $qs->result();
		//}
		return $result;
    }
	
    public function getRintaPerNo($period, $nd) {
		$result = array();
		$sql = "SELECT * FROM TAB WHERE TNAME='CUST_AMA_".$period."'";
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) {
			$sql = "SELECT ND_APPELE, TGL_JAM, DURASI, BIAYA, ND, TUJUAN FROM CUST_AMA_".$period." WHERE ND='".$nd."'";
			//$sql = "SELECT ND_APPELE, TO_CHAR(TGL_JAM, 'DD/MM/YYYY HH24:MI:SS') TGL_JAM, DURASI, BIAYA, ND, TUJUAN FROM CUST_AMA_".$period." WHERE ND='".$nd."'";
			$sql .= " ORDER BY TGL_JAM";
			$qama = $this->db->query($sql); 
			if($qama->num_rows() > 0) $result = $qama->result();
		}
		return $result;
    }
    
  public function getRintaAjus($period, $nd) {
		  $result = array();
		  $sql = "SELECT * FROM TAGIHAN WHERE BULTAG=".$period." AND NO_FASTEL='".$nd."'";
		  $qama = $this->db->query($sql); 
			if($qama->num_rows() > 0) $result = $qama->result();
			return $result;
		}
		
  public function getRintaDet($period, $nd) {
		  $result = array();
		  $sql = "SELECT * FROM RINCI_AJUSMENT WHERE BULTAG=".$period." AND FASTEL='".$nd."'";
		  $qama = $this->db->query($sql); 
			if($qama->num_rows() > 0) $result = $qama->result();
			return $result;
		}
	
	public function getPeriodeRinta($cond) {
		$result = array();
		$sql = "select substr(tname,12,6) periode from tab";
		if($cond!='') $sql .= " WHERE ".$cond;
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) $result = $q->result();
		return $result;
    }
	
	public function getListsSummary($period="",$cond) {
		$result = array();
		$sql = "select * from CUST_RINTA_".$period;
		if($cond!='') $sql .= " WHERE ".$cond;
		$sql .= " ORDER BY nd";
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) $result = $q->result();
		return $result;
    }

	
}
?>