<?php
class M_report extends CI_Model {

    function __construct() {
        parent::__construct();
    }
	
	public function trendFeePerPgl($pgl_id, $year) {
		$result = array();
		$sql = "SELECT A.PGL_ID, B.PGL_NAME, A.PERIOD, ".
			"SUM(A.FEE_TOTAL) FEE_TOTAL, SUM(A.FEE_NON_TAX) FEE_NON_TAX ".
			" FROM NPK A, CUST_PGL B WHERE A.PGL_ID=B.PGL_ID AND A.PGL_ID=".$pgl_id.
			" AND A.PERIOD LIKE '".$year."%' GROUP BY A.PGL_ID, B.PGL_NAME, A.PERIOD".
			" ORDER BY A.PERIOD";
		$q = @$this->db->query($sql); 
		if(@$q->num_rows() > 0) $result = $q->result();
		return $result;
    }
	
	public function trendFeeTotal($year) {
		$result = array();
		$sql = "SELECT A.PERIOD, ".
			"SUM(A.FEE_TOTAL) FEE_TOTAL, SUM(A.FEE_NON_TAX) FEE_NON_TAX ".
			" FROM NPK A, CUST_PGL B WHERE A.PGL_ID=B.PGL_ID ".
			" AND A.PERIOD LIKE '".$year."%' GROUP BY A.PERIOD".
			" ORDER BY A.PERIOD";
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) $result = $q->result();
		return $result;
    }
	
	public function trendRevPerPgl($pgl_id, $year) {
		$result = array();
		$sql = "SELECT A.PGL_ID, B.PGL_NAME, A.PERIOD, SUM(C.CF_NOM) CF_NOM ".
			" FROM NPK A, CUST_PGL B, NPK_PROCESS C WHERE A.PGL_ID=B.PGL_ID AND A.NPK_ID=C.NPK_ID ".
			" AND A.PGL_ID=".$pgl_id." AND A.PERIOD LIKE '".$year."%' AND C.CF_ID=27".
			" GROUP BY A.PGL_ID, B.PGL_NAME, A.PERIOD".
			" ORDER BY A.PERIOD";
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) {
			foreach( $q->result() as $k => $r) {
				$result[substr($r->PERIOD,4,2)] = $r;
			}
		}
		return $result;
    }
	
	public function trendRevTotal($year) {
		$result = array();
		$sql = "SELECT A.PERIOD, ".
			"SUM(A.FEE_TOTAL) FEE_TOTAL, SUM(A.FEE_NON_TAX) FEE_NON_TAX ".
			" FROM NPK A, CUST_PGL B, NPK_PROCESS C WHERE A.PGL_ID=B.PGL_ID ".
			" AND A.PERIOD LIKE '".$year."%' GROUP BY A.PERIOD".
			" ORDER BY A.PERIOD";
		$sql = "SELECT A.PERIOD, SUM(C.CF_NOM) CF_NOM ".
			" FROM NPK A, NPK_PROCESS C WHERE A.NPK_ID=C.NPK_ID ".
			" AND A.PERIOD LIKE '".$year."%' AND C.CF_ID=27".
			" GROUP BY A.PERIOD".
			" ORDER BY A.PERIOD";
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) {
			foreach( $q->result() as $k => $r) {
				$result[substr($r->PERIOD,4,2)] = $r;
			}
		}
		return $result;
    }
	
	public function getNDChurnHist($ten_id, $period) {
	    $result = array();
		$sql = "SELECT * FROM TEN_ND_HIST WHERE TEN_ID=".$ten_id." AND PERIOD='".$period."' AND AKTIF=3";
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) $result = $q->result();
		return $result;
    }
    
    public function getBadDebt($period) {
	    $result = array();
            $sql = "SELECT GL_ACCOUNT, SUM(BILL_AMOUNT) BILL_AMOUNT".
                    " FROM REVENUE_NP_BADDEBT_PAY WHERE BIL_PERIOD='".$period."' AND PAY_STATUS='sudah bayar'".
                    " GROUP BY GL_ACCOUNT";
            $q = $this->db->query($sql); 
            if($q->num_rows() > 0) $result = $q->result();
            return $result;
    }
    
    public function getBadDebtTotal($period) {
	    $result = 0;
            $sql = "SELECT SUM(BILL_AMOUNT) BILL_AMOUNT".
                    " FROM REVENUE_NP_BADDEBT_PAY WHERE BIL_PERIOD='".$period."' AND PAY_STATUS='sudah bayar' ";
            $q = $this->db->query($sql); 
            if($q->num_rows() > 0) {
                foreach($q->result() as $k=>$r) $result = $r->BILL_AMOUNT;
            }
            return $result;
    }

    public function getTagNonPOTS($period, $pgl_id="") {
	    $result = array();
            $sql = "SELECT * FROM TAB WHERE TNAME='CUST_RINTA_NONPOTS_".$period."'";
            $q = $this->db->query($sql); 
            if($q->num_rows() > 0) {
                $sql = "SELECT D.PGL_NAME, CUST_NAME, PROD_PERIOD, BILL_MNY, PRODUCT_NAME, CONTRACT_NO, ".
                        "TO_CHAR(START_DAT, 'DD/MM/YYYY') START_DAT, TO_CHAR(END_DAT, 'DD/MM/YYYY') END_DAT, ABONEMEN ".
                        " FROM CUST_RINTA_NONPOTS_".$period." A, TEN_ND_NONPOTS B, PGL_TEN C, CUST_PGL D ".
                        " WHERE A.ACCOUNT_NUM=B.ACCOUNT_NUM(+) AND B.TEN_ID=C.TEN_ID(+) AND C.PGL_ID=D.PGL_ID(+) ";
                if($pgl_id!="") 
                    $sql .= " AND D.PGL_ID='".$pgl_id."'";
                $sql .= " ORDER BY D.PGL_NAME, CUST_NAME";
                $q = $this->db->query($sql); 
                if($q->num_rows() > 0) $result = $q->result();
            }
            return $result;
    }


}
?>