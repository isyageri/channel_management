<?php
class M_compfee extends CI_Model {
	public $cf_types;
	public $line_types;

    function __construct() {
        parent::__construct();
        $this->cf_types = array("ORIG"=>"Common", "UDEF"=>"User Defined", "SDEF"=>"Automatically System Defined", "MANU"=>"Manual Billing Component");
        $this->line_types = array("POT"=>"POTS", "SPD"=>"Speedy", "DAT"=>"Data Non Speedy", "unk"=>"Unknown");
    }
    
    
    // Component Fee
    public function getLists($cond="", $order="") {
	    $result = array();
		$sql = "SELECT * FROM COM_FEE";
		if($cond!='') $sql .= " WHERE ".$cond;
		if($order!='') $sql .= " ORDER BY ".$order;
		else $sql .= " ORDER BY CF_NAME";

		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) $result = $q->result();
		return $result;
    }
    
    public function insert($cf_name, $cf_type, $line_type, $str_formula, $cf_caption) {
	    $new_id = $this->getNewCfId();
	    $sql = "INSERT INTO COM_FEE(CF_ID, CF_NAME, CF_TYPE, LINE_TYPE, STR_FORMULA, CF_CAPTION) ".
	    	"VALUES(".$new_id.", '".$cf_name."', '".$cf_type."', '".$line_type."', '".$str_formula."', '".$cf_caption."') ";
		$q = $this->db->query($sql); 
    }
    
    public function insertSys($str_formula) {
	    $new_id = $this->getNewCfId();
	    $sql = "INSERT INTO COM_FEE(CF_ID, CF_NAME, CF_TYPE, LINE_TYPE, STR_FORMULA) ".
	    	"VALUES(".$new_id.", 'SYS".substr("00000000".$new_id, -7, 7)."', 'SDEF', 'unk', '".$str_formula."') ";
		$q = $this->db->query($sql); 
		return $new_id;
    }
    
    public function update($cf_id, $cf_name, $cf_type, $line_type, $str_formula, $cf_caption) {
	    $sql = "UPDATE COM_FEE SET CF_NAME='".$cf_name."', CF_TYPE='".$cf_type."', LINE_TYPE='".$line_type.
	    	"', STR_FORMULA='".$str_formula."', CF_CAPTION='".$cf_caption."' WHERE CF_ID=".$cf_id;
		$q = $this->db->query($sql); 
    }
	
	public function updateStrFormula($cf_id, $str_formula) {
	    $sql = "UPDATE COM_FEE SET STR_FORMULA='".$str_formula."' WHERE CF_ID=".$cf_id;
		$q = $this->db->query($sql); 
    }
    
    public function remove($cf_id) {
	    $sql = "DELETE FROM COM_FEE WHERE CF_ID=".$cf_id;
		$q = $this->db->query($sql);
    }
    
    private function getNewCfId() {
		$q = $this->db->query("SELECT MAX(CF_ID)+1 N FROM COM_FEE");
		foreach($q->result() as $r) $n = $r->N;
		if ($n=='' || $n=='0') $n=1;
		return $n;
	}
	
	
	// Tiering
    public function getTier($cond="") {
	    $result = array();
		$sql = "SELECT * FROM TIER";
		if($cond!='') $sql .= " WHERE ".$cond;

		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) $result = $q->result();
		return $result;
    }
    
    public function insertTier($tier_name, $tier_params, $tier_desc) {
	    $new_id = $this->getNewTierId();
	    $sql = "INSERT INTO TIER(TIER_ID, TIER_NAME, TIER_PARAMS, TIER_DESC) ".
	    	"VALUES(".$new_id.", '".$tier_name."', '".$tier_params."', '".$tier_desc."') ";
		$q = $this->db->query($sql); 
    }
    
    public function updateTier($tier_id, $tier_name, $tier_params, $tier_desc) {
	    $sql = "UPDATE TIER SET TIER_NAME='".$tier_name."', TIER_PARAMS='".$tier_params."', TIER_DESC='".$tier_desc."' ".
	    	" WHERE TIER_ID=".$tier_id;
		$q = $this->db->query($sql); 
    }
    
    public function removeTier($tier_id) {
	    $sql = "DELETE FROM TIER WHERE TIER_ID=".$tier_id;
		$q = $this->db->query($sql);
    }
    
    private function getNewTierId() {
		$q = $this->db->query("SELECT MAX(TIER_ID)+1 N FROM TIER");
		foreach($q->result() as $r) $n = $r->N;
		if ($n=='' || $n=='0') $n=1;
		return $n;
	}
	
	
	// Tier Condition
    public function getTierCond($cond="") {
	    $result = array();
		$sql = "SELECT * FROM TIER_COND";
		if($cond!='') $sql .= " WHERE ".$cond;
		$sql .= " ORDER BY SEQ_NO";
		$q = $this->db->query($sql);
        echo $sql;
		if($q->num_rows() > 0) $result = $q->result();
		return $result;
    }
    
    public function insertTierCond($tier_id, $seq_no, $str_cond, $nresult) {
	    $sql = "INSERT INTO TIER_COND(TIER_ID, SEQ_NO, STR_COND, NRESULT) ".
	    	"VALUES(".$tier_id.", ".$seq_no.", '".$str_cond."', ".$nresult.") ";
		$q = $this->db->query($sql); 
    }
    
    public function updateTierCond($tier_id, $seq_no, $str_cond, $nresult) {
	    $sql = "UPDATE TIER_COND SET STR_COND='".$str_cond."', NRESULT=".$nresult.
	    	" WHERE TIER_ID=".$tier_id." AND SEQ_NO=".$seq_no;
		$q = $this->db->query($sql); 
    }
    
    public function removeTierCond($tier_id, $seq_no) {
	    $sql = "DELETE FROM TIER_COND WHERE TIER_ID=".$tier_id." AND SEQ_NO=".$seq_no;
		$q = $this->db->query($sql);
    }
    
    
}
?>