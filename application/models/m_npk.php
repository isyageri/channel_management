<?php
class M_npk extends CI_Model {
	public $methods;
	public $status;
	public $months;
	public $years;
	public $cellvaluetypes;

    function __construct() {
        parent::__construct();
        $this->methods = array("BIL"=>"By Billing", "COL"=>"By Collection");
        $this->status = array(1=>"Draft", 2=>"Calculating", 3=>"Finished Calculate", 4=>"Arrange Table", 5=>"Arrange Data", 6=>"Preview NPK", 7=>"Preview BA", 9=>"Locked");
        $this->months = array("01"=>"Jan", "02"=>"Feb", "03"=>"Mar", "04"=>"Apr", "05"=>"May", "06"=>"Jun", "07"=>"Jul", "08"=>"Aug", "09"=>"Sep", "10"=>"Okt", "11"=>"Nov", "12"=>"Des");
        $this->years = array(2010=>2010, 2011=>2011, 2012=>2012, 2013=>2013, 2014=>2014, 2015=>2015, 2016=>2016, 2017=>2017, 2018=>2018);
		$this->cellvaluetypes = array("LBL"=>"Text", "LCF"=>"Label of Component", "NOM"=>"Nominal", "PCT"=>"Percentage", "CUR"=>"Currency");
    }
    
    
    // Draft NPK
    public function getLists($cond="") {
	    $result = array();
		$sql = "SELECT * FROM (SELECT A.NPK_ID, A.PGL_ID, A.PERIOD, A.METHOD, A.UPDATE_DATE, A.UPDATE_BY, A.SIGN_NAME_1, A.SIGN_POS_1, ".
			"A.SIGN_NAME_2, A.SIGN_POS_2, A.FEE_NON_TAX, A.FEE_TAX, A.FEE_TOTAL, A.FEE_CF_ID, A.STATUS, ".
			"A.MOU_NO, TO_CHAR(A.MOU_DATE, 'DD/MM/YYYY') MOU_DATE, B.PGL_NAME ".
			"FROM NPK A, CUST_PGL B WHERE A.PGL_ID=B.PGL_ID) ";
		if($cond!='') $sql .= " WHERE ".$cond;
		$sql .= " ORDER BY PGL_NAME, PERIOD DESC";
        //echo $sql;
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) $result = $q->result();
		return $result;
    }

	public function getPrevPeriod($period) {
		$prev = (int)$period - 1;
		if(substr($period, 4, 2)=="01") {
			$a = substr($period, 0, 4) - 1;
			$b = "12";
			$prev = $a.$b;
		}
		return $prev;
	}
    
    public function insert($pgl_id, $period, $method, $update_date, $update_by, $sign_name_1, $sign_pos_1, $sign_name_2, $sign_pos_2) {
	    $new_id = $this->getNewNPKId();
	    $sql = "INSERT INTO NPK(NPK_ID, PGL_ID, PERIOD, METHOD, UPDATE_DATE, UPDATE_BY, SIGN_NAME_1, SIGN_POS_1, SIGN_NAME_2, SIGN_POS_2, ".
	    	"FEE_NON_TAX, FEE_TAX, FEE_TOTAL, STATUS) VALUES(".$new_id.", ".$pgl_id.", '".$period."', '".$method."', ".
	    	"TO_DATE('".$update_date."', 'DD/MM/YYYY'), ".$update_by.", '".$sign_name_1."', '".$sign_pos_1."', '".
	    	$sign_name_2."', '".$sign_pos_2."', 0, 0, 0, 1) ";
		$q = $this->db->query($sql); 
    }
    
    public function update($npk_id, $pgl_id, $period, $method, $update_date, $update_by, $sign_name_1, $sign_pos_1, $sign_name_2, $sign_pos_2) {
	    $sql = "UPDATE NPK SET PGL_ID=".$pgl_id.", PERIOD='".$period."', METHOD='".$method."', ".
	    	"UPDATE_DATE=TO_DATE('".$update_date."', 'DD/MM/YYYY'), UPDATE_BY=".$update_by.", SIGN_NAME_1='".$sign_name_1.
	    	"', SIGN_POS_1='".$sign_pos_1."', SIGN_NAME_2='".$sign_name_2."', SIGN_POS_2='".$sign_pos_2."' WHERE NPK_ID=".$npk_id;
		$q = $this->db->query($sql); 
    }
    
    public function setFee($npk_id, $fee_non_tax, $fee_tax, $fee_total) {
	    $sql = "UPDATE NPK SET FEE_NON_TAX=".$fee_non_tax.", FEE_TAX=".$fee_tax.", FEE_TOTAL=".$fee_total.", STATUS=2 WHERE NPK_ID=".$npk_id;
		$q = $this->db->query($sql);
    }
    
    public function setStatus($npk_id, $status) {
	    $sql = "UPDATE NPK SET STATUS=".$status." WHERE NPK_ID=".$npk_id;
		$q = $this->db->query($sql);
    }
    
    public function remove($npk_id) {
	    $sql = "DELETE FROM NPK WHERE NPK_ID=".$npk_id;
		$q = $this->db->query($sql);
		$sql = "DELETE FROM NPK_PROCESS WHERE NPK_ID=".$npk_id;
		$q = $this->db->query($sql);
		$sql = "DELETE FROM NPK_TABLE WHERE NPK_ID=".$npk_id;
		$q = $this->db->query($sql);
    }
    
    private function getNewNPKId() {
		$q = $this->db->query("SELECT MAX(NPK_ID)+1 N FROM NPK");
		foreach($q->result() as $r) $n = $r->N;
		if ($n=='' || $n=='0') $n=1;
		return $n;
	}
	
	public function isValidNPK($pgl_id, $period) {
		$result = FALSE;
		$cmonth = substr($period, 4, 2); $cyear = substr($period, 0, 4);
		$sql = "SELECT PGL_ID, TO_CHAR(MAX(END_DATE), 'MM') MM, TO_CHAR(MAX(END_DATE), 'YYYY') YYYY ".
			"FROM MOU WHERE PGL_ID=".$pgl_id." GROUP BY PGL_ID";
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) {
			foreach($q->result() as $r) {
				$moumonth = $r->MM;
				$mouyear = $r->YYYY;
			}
			if($mouyear > $cyear) {
				$result = TRUE;
			} elseif($mouyear == $cyear) {
				if($moumonth >= $cmonth) $result = TRUE;
			}
		} 
		return $result;
	}

	// Recent Saved
	public function getRecent($cond="") {
	    $result = array();
		$sql = "SELECT * FROM NPK_RC";
		if($cond!='') $sql .= " WHERE ".$cond;
		$sql .= " ORDER BY NPK_RC_NAME";
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) $result = $q->result();
		return $result;
    }

	private function getNewNPKRCId() {
		$q = $this->db->query("SELECT MAX(NPK_RC_ID)+1 N FROM NPK_RC");
		foreach($q->result() as $r) $n = $r->N;
		if ($n=='' || $n=='0') $n=1;
		return $n;
	}

	public function saveTo($npk_id, $npk_rc_name) {
		$new_rc_id = $this->getNewNPKRCId();
		$sql = "INSERT INTO NPK_PROCESS_RC(NPK_RC_ID, STEP, CF_ID) SELECT ".$new_rc_id.", STEP, CF_ID FROM NPK_PROCESS WHERE NPK_ID=".$npk_id;
		$q = $this->db->query($sql);
		$sql = "INSERT INTO NPK_RC(NPK_RC_ID, NPK_RC_NAME, METHOD, FEE_CF_ID) ".
			"SELECT ".$new_rc_id.", '".$npk_rc_name."', METHOD, FEE_CF_ID FROM NPK WHERE NPK_ID=".$npk_id;
		$q = $this->db->query($sql);
	}

	public function loadFormula($npk_rc_id, $npk_id) {
		$sql = "DELETE FROM NPK_PROCESS WHERE NPK_ID=".$npk_id." AND STEP<>0";
		$q = $this->db->query($sql);
		$sql = "INSERT INTO NPK_PROCESS(NPK_ID, STEP, CF_ID, STR_FORMULA) ".
			"SELECT ".$npk_id.", A.STEP, A.CF_ID, B.STR_FORMULA FROM NPK_PROCESS_RC A, COM_FEE B ".
			"WHERE A.CF_ID=B.CF_ID AND A.NPK_RC_ID=".$npk_rc_id." AND A.STEP<>0";
		$q = $this->db->query($sql);
		$rc = $this->getRecent("NPK_RC_ID=".$npk_rc_id);
		if(count($rc) > 0) {
			$sql = "UPDATE NPK SET FEE_CF_ID=".$rc[0]->FEE_CF_ID." WHERE NPK_ID=".$npk_id;
			$q = $this->db->query($sql);
		}
	}
	
	
	// Step Calculation
	public function step0($npk_id) {
		$sql = "SELECT * FROM NPK_PROCESS WHERE NPK_ID=".$npk_id." AND STEP=0";
		$q = $this->db->query($sql); 
		if( !($q->num_rows() > 0)) {
			$this->absolutelystep0($npk_id);
		}
	}
	
	public function absolutelystep0($npk_id) {
		$sql = "BEGIN NPK_PRE_CALC(".$npk_id."); END;";
		@$this->db->query($sql);
	}
	
	public function getProcess($npk_id, $step) {
		$result = array();
		$sql = "SELECT A.NPK_ID, A.STEP, A.CF_ID, A.CF_NOM, A.STR_FORMULA, B.CF_NAME, B.CF_CAPTION FROM NPK_PROCESS A, COM_FEE B ".
			"WHERE A.CF_ID=B.CF_ID AND A.NPK_ID=".$npk_id." AND A.STEP=".$step." ORDER BY B.CF_NAME";
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) $result = $q->result();
		return $result;
	}
	
	public function insertProcess($npk_id, $step, $cf_id, $cf_nom, $str_formula) {
	    $sql = "INSERT INTO NPK_PROCESS(NPK_ID, STEP, CF_ID, CF_NOM, STR_FORMULA) ".
	    	" VALUES(".$npk_id.", ".$step.", ".$cf_id.", ".$cf_nom.", '".$str_formula."') ";
		$q = $this->db->query($sql); 
    }
	
	public function updateProcess($npk_id, $step, $cf_id, $cf_nom, $str_formula) {
	    $sql = "UPDATE NPK_PROCESS SET CF_NOM=".$cf_nom.", STR_FORMULA='".$str_formula."' ".
			" WHERE NPK_ID=".$npk_id." AND STEP=".$step." AND CF_ID=".$cf_id;
		$q = $this->db->query($sql);
    }
	
	public function removeProcess($npk_id, $step) {
		$sql = "DELETE FROM NPK_PROCESS WHERE NPK_ID=".$npk_id." AND STEP=".$step;
		$q = $this->db->query($sql);
	}

	public function getStepNum($npk_id) {
		$n=0;
		$q = $this->db->query("SELECT MAX(STEP) N FROM NPK_PROCESS WHERE NPK_ID=".$npk_id);
		foreach($q->result() as $r) $n = $r->N;
		return $n;
	}
	public function getProcessCom($npk_id, $step, $cf_id) {
		$result = array();
		$sql = "SELECT A.NPK_ID, A.STEP, A.CF_ID, A.CF_NOM, A.STR_FORMULA, B.CF_NAME, B.CF_CAPTION FROM NPK_PROCESS A, COM_FEE B ".
			"WHERE A.CF_ID=B.CF_ID AND A.NPK_ID=".$npk_id." AND A.STEP=".$step." AND A.CF_ID=".$cf_id." ORDER BY B.CF_NAME";
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) $result = $q->result();
		return $result;
	}
	
	public function getProcessFml($npk_id, $str_formula) {
		$result = array();
		$sql = "SELECT NPK_ID, STEP, CF_ID, CF_NOM, STR_FORMULA FROM NPK_PROCESS ".
			"WHERE NPK_ID=".$npk_id." AND STR_FORMULA='".$str_formula."'";
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) {
			$result = $q->result();
			$result = $result[0];
		}
		return $result;
	}
	
	public function removeProcessCom($npk_id, $step, $cf_id) {
		$sql = "DELETE FROM NPK_PROCESS WHERE NPK_ID=".$npk_id." AND STEP=".$step." AND CF_ID=".$cf_id;
		$q = $this->db->query($sql);
	}
	
	public function getNom($npk_id, $cf_name) {
		$result = 0;
		$sql = "SELECT A.NPK_ID, A.STEP, A.CF_ID, A.CF_NOM, A.STR_FORMULA, B.CF_NAME, B.CF_TYPE FROM NPK_PROCESS A, COM_FEE B ".
			"WHERE A.CF_ID=B.CF_ID AND A.NPK_ID=".$npk_id." AND UPPER(B.CF_NAME)='".strtoupper($cf_name)."'";
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) {
			foreach($q->result() as $k=>$v) 
				$result = $v->CF_NOM;
		} else {
			$ci = & get_instance();
			$ci->load->model("M_compfee");
			$def = $ci->M_compfee->getLists("CF_NAME='".$cf_name."'");
			$result = $this->parseFormula($npk_id, $def[0]->STR_FORMULA);
		}
		return $result;
	}
	
	public function setCFAsFee($npk_id, $cf_id) {
		$cf_nom = 0;
		$sql = "SELECT CF_NOM FROM NPK_PROCESS WHERE NPK_ID=".$npk_id." AND CF_ID=".$cf_id;
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) {
			foreach($q->result() as $k=>$v)
				$cf_nom = $v->CF_NOM;
		}
		$sql = "UPDATE NPK SET FEE_CF_ID=".$cf_id.", FEE_NON_TAX=".$cf_nom." WHERE NPK_ID=".$npk_id;
		$q = $this->db->query($sql);
	}
	
	public function setNettoFee($npk_id) {
		$sql = "UPDATE NPK SET FEE_TAX=CEIL(0.10*FEE_NON_TAX) WHERE NPK_ID=".$npk_id;
		$q = $this->db->query($sql);
		$sql = "UPDATE NPK SET FEE_TOTAL=FEE_NON_TAX+FEE_TAX WHERE NPK_ID=".$npk_id;
		$q = $this->db->query($sql);
	}
	
	public function updateNom($npk_id, $step, $str_formula, $cf_nom) {
		$sql = "UPDATE NPK_PROCESS SET CF_NOM=".$cf_nom." WHERE NPK_ID=".$npk_id." AND STEP=".$step." AND STR_FORMULA='".$str_formula."'";
		$q = $this->db->query($sql);
	}
	
	public function parseFormula($npk_id, $str_formula) {
		$tmp = $this->parseFormulaUntilPercent($npk_id, $str_formula);

		$tmp = str_replace("%", "/100", $tmp);
		eval("\$n = ".$tmp.";");
                eval("if(\$n < 100 ) \$n = round(\$n, 2);");
		eval("else \$n = ceil(\$n);");
		return $n;
	}
	
	public function parseFormulaUntilPercent($npk_id, $str_formula) {
		$n = 0;
		$tmp = $str_formula;
		$tmp = str_replace(" ","", $str_formula);
		
		$com_fee = array();
		preg_match_all("/\[(.*?)\]/", $tmp, $com_fee);
		if(is_array($com_fee[1])) {
			foreach($com_fee[1] as $k=>$item) {
				$nom = $this->getNom($npk_id, $item);
				$tmp = str_replace("[".$item."]", $nom, $tmp);
			}
		}

		$tier = array();
		preg_match_all("/\{(.*?)\}/", $tmp, $tier);
		if(is_array($tier[1])) {
			foreach($tier[1] as $k=>$item) {
				$nom = $this->parseTier($npk_id, $item);
				$tmp = str_replace("{".$item."}", $nom, $tmp);
			}
		}

		return $tmp;
	}

	public function parseTier($npk_id, $str_tier) {
		$result = 0; 
		$tier_name = substr($str_tier, 0,strpos($str_tier, ":"));
		$tier_pv   = substr($str_tier, strpos($str_tier, ":")+1, strlen($str_tier) );				// pv = param value, pva = param value array
		$tier_pva  = explode(",", $tier_pv);
		$ci = & get_instance();
		$ci->load->model("M_compfee");
		$tier = $ci->M_compfee->getTier("TIER_NAME='".$tier_name."'");
		if( count($tier)>0 ) {
			$tier_p = explode(",", $tier[0]->TIER_PARAMS);											// p = variable of params
			if(count($tier_p) == count($tier_pva)) {
				foreach($tier_p as $k => $v) {
					eval ("\$".$v."=".$tier_pva[$k].";");
				}
				$tier_cond = $ci->M_compfee->getTierCond("TIER_ID=".$tier[0]->TIER_ID);
				$i = 0;
				$evalstr = "";
				foreach($tier_cond as $t => $tv) {
					$cdt = $tv->STR_COND;
					foreach($tier_p as $k => $v) {
						$cdt = str_replace($v, "\$".$v, $cdt);
					}
					if($i==0)
						$evalstr = "if(".$cdt.") { \n";
					else 
						$evalstr .= "} elseif(".$cdt.") {";
					$evalstr .= "\$result=".$tv->NRESULT.";\n";
					$i++;
				}
				if(count($tier_cond)>0) $evalstr .= "}";

				eval ($evalstr);
			}
		}
		eval ("\$result=".$result.";");
		return $result;
	}

	// Table
	public function getTableFormat($npk_id) {
		$result = array();
		$sql = "SELECT * FROM NPK_TABLE WHERE NPK_ID=".$npk_id;
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) $result = $q->result();
		return $result;
	}
	
	public function clearTableFormat($npk_id) {
		$sql = "DELETE FROM NPK_TABLE WHERE NPK_ID=".$npk_id;
		$q = $this->db->query($sql); 
	}
	
	public function clearMaskTableFormat($npk_id, $mask_col, $mask_row) {
		$sql = "DELETE FROM NPK_TABLE WHERE NPK_ID=".$npk_id." AND CELL_COL > ".$mask_col;
		$q = $this->db->query($sql); 
		$sql = "DELETE FROM NPK_TABLE WHERE NPK_ID=".$npk_id." AND CELL_ROW > ".$mask_row;
		$q = $this->db->query($sql); 
	}

	public function prepareCell($npk_id, $ncol, $nrow) {
		for($i=1; $i<=$ncol; $i++) {
			for($j=0; $j<=$nrow; $j++) {
				$sql = "INSERT INTO NPK_TABLE(NPK_ID, CELL_COL, CELL_ROW) ".
					"VALUES(".$npk_id.", ".$i.", ".$j.")";
				$q = $this->db->query($sql);
			}
		}
	}
	
	public function prepareMaskCell($npk_id, $ncol, $nrow) {
		$this->clearMaskTableFormat($npk_id, $ncol, $nrow);
		$max_col_num = $this->getColNum($npk_id);
		$max_row_num = $this->getRowNum($npk_id);
		
		for($i=($max_col_num+1); $i<=$ncol; $i++) {
			for($j=0; $j<=$nrow; $j++) {
				$sql = "INSERT INTO NPK_TABLE(NPK_ID, CELL_COL, CELL_ROW) ".
					"VALUES(".$npk_id.", ".$i.", ".$j.")";
				$q = $this->db->query($sql);
			}
		}
		
		for($i=1; $i<=$max_col_num; $i++) {
			for($j=($max_row_num+1); $j<=$nrow; $j++) {
				$sql = "INSERT INTO NPK_TABLE(NPK_ID, CELL_COL, CELL_ROW) ".
					"VALUES(".$npk_id.", ".$i.", ".$j.")";
				$q = $this->db->query($sql);
			}
		}
	}

	public function setCell($npk_id, $cell_col, $cell_row, $value_as, $value) {
		if($value_as=="LBL") 
			$sql = "UPDATE NPK_TABLE SET VALUE_AS='".$value_as."', CF_LABEL='".$value."' ".
				" WHERE NPK_ID=".$npk_id." AND CELL_COL=".$cell_col." AND CELL_ROW=".$cell_row;
		else 
			$sql = "UPDATE NPK_TABLE SET VALUE_AS='".$value_as."', CF_ID=".$value.
				" WHERE NPK_ID=".$npk_id." AND CELL_COL=".$cell_col." AND CELL_ROW=".$cell_row;
		$q = $this->db->query($sql);
	}

	public function setCellHeader($npk_id, $cell_col, $value) {
		$this->setCell($npk_id, $cell_col, 0, "LBL", $value);
	}

	public function getColNum($npk_id) {
		$n=0;
		$q = $this->db->query("SELECT MAX(CELL_COL) N FROM NPK_TABLE WHERE NPK_ID=".$npk_id);
		foreach($q->result() as $r) $n = $r->N;
		return $n;
	}

	public function getRowNum($npk_id) {
		$n=0;
		$q = $this->db->query("SELECT MAX(CELL_ROW) N FROM NPK_TABLE WHERE NPK_ID=".$npk_id);
		foreach($q->result() as $r) $n = $r->N;
		return $n;
	}

	public function getCell($npk_id, $cell_col, $cell_row) {
		$result = array();
		$sql = "SELECT * FROM NPK_TABLE WHERE NPK_ID=".$npk_id." AND CELL_COL=".$cell_col." AND CELL_ROW=".$cell_row;
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) $result = $q->result();
		return $result;
	}
	
	public function getColsData($npk_id, $cell_row) {
		$result = array();
		$sql = "SELECT * FROM NPK_TABLE WHERE NPK_ID=".$npk_id." AND CELL_ROW=".$cell_row;
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) {
			foreach($q->result() as $k => $v) {
				$result[$v->CELL_COL]['VALUE_AS'] = $v->VALUE_AS;
				$result[$v->CELL_COL]['CF_ID'] = $v->CF_ID;
				$result[$v->CELL_COL]['CF_LABEL'] = $v->CF_LABEL;
			}
		}
		return $result;
	}
	
	public function getRowsData($npk_id, $cell_col) {
		$result = array();
		$sql = "SELECT * FROM NPK_TABLE WHERE NPK_ID=".$npk_id." AND CELL_COL=".$cell_col;
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) {
			foreach($q->result() as $k => $v) {
				$result[$v->CELL_ROW]['VALUE_AS'] = $v->VALUE_AS;
				$result[$v->CELL_ROW]['CF_ID'] = $v->CF_ID;
				$result[$v->CELL_ROW]['CF_LABEL'] = $v->CF_LABEL;
			}
		}
		return $result;
	}
	
	public function getProcessComView($npk_id, $cf_id, $value_as) {
		$result = "";
		$sql = "SELECT A.NPK_ID, A.STEP, A.CF_ID, A.CF_NOM, A.STR_FORMULA, B.CF_NAME, B.CF_CAPTION FROM NPK_PROCESS A, COM_FEE B ".
			"WHERE A.CF_ID=B.CF_ID AND A.NPK_ID=".$npk_id." AND A.CF_ID=".$cf_id." ORDER BY B.CF_NAME";
		if($cf_id!="") {
			$q = $this->db->query($sql); 
			if($q->num_rows() > 0) {
				$rec = $q->result(); $rec = $rec[0];
				switch($value_as) {
					case "LCF":
						$result = $rec->CF_CAPTION;
						break;
					case "NOM":
						$result = round($rec->CF_NOM);
						break;
					case "PCT":
						$result = $rec->STR_FORMULA;
						$result = $this->parseFormulaUntilPercent($npk_id, $rec->STR_FORMULA);
						preg_match_all("/([0-9.]+)%/", $result, $tmp);
						$result = $tmp[0][0];
						break;
					case "FML":
						$result = $rec->STR_FORMULA;
						break;
				}
			} else {
				$sql = "SELECT * FROM COM_FEE WHERE CF_TYPE='UDEF' AND CF_ID=".$cf_id;
				$q2 = $this->db->query($sql);
				$rec = $q2->result(); $rec = $rec[0];
				if($q2->num_rows() > 0) {
					switch($value_as) {
						case "LCF":
							$result = $rec->CF_CAPTION;
							break;
						case "NOM":
							$result = $rec->STR_FORMULA;
							$result = $this->parseFormula($npk_id, $rec->STR_FORMULA);
							break;
						case "PCT":
							$result = $rec->STR_FORMULA;
							$result = $this->parseFormulaUntilPercent($npk_id, $rec->STR_FORMULA);
							preg_match_all("/([0-9.]+)%/", $result, $tmp);
							$result = $tmp[0][0];
							break;
						case "FML":
							$result = $rec->STR_FORMULA;
							break;
					}
				}
			}
		}
		return $result;
	}
	
	public function loadTableFormat($npk_id_old, $npk_id_new) {
		$this->clearTableFormat($npk_id_new);
		$sql = "INSERT INTO NPK_TABLE(NPK_ID, CELL_COL, CELL_ROW, VALUE_AS, CF_ID, CF_LABEL) ".
			"SELECT ".$npk_id_new.", CELL_COL, CELL_ROW, VALUE_AS, CF_ID, CF_LABEL FROM NPK_TABLE WHERE NPK_ID=".
			$npk_id_old;
		$q = $this->db->query($sql);
		
		$sql = "SELECT A.NPK_ID, A.CELL_COL, A.CELL_ROW, A.VALUE_AS, A.CF_ID, C.STR_FORMULA".
			" FROM NPK_TABLE A, NPK_TABLE B, NPK_PROCESS C ".
			" WHERE A.CELL_COL=B.CELL_COL AND A.CELL_ROW=B.CELL_ROW ".
			" AND A.NPK_ID=".$npk_id_old." AND C.NPK_ID=".$npk_id_old.
			" AND B.NPK_ID=".$npk_id_new." AND C.CF_ID=A.CF_ID";
		$q = $this->db->query($sql);
		if($q->num_rows() > 0) {
			foreach($q->result() as $k => $r) {
				if(trim($r->STR_FORMULA)!="") {
					$cf = $this->getProcessFml($npk_id_new, $r->STR_FORMULA); 
					if(isset($cf->CF_ID)) {
						$sql = "UPDATE NPK_TABLE SET CF_ID=".$cf->CF_ID." WHERE NPK_ID=".$npk_id_new.
							" AND CELL_COL=".$r->CELL_COL." AND CELL_ROW=".$r->CELL_ROW;
						$qupd = $this->db->query($sql);
					}
				}
			}
		}
	}


	// MOU
	public function setMOU($npk_id, $mou_no, $mou_date) {
		$sql = "UPDATE NPK SET MOU_NO='".$mou_no."', MOU_DATE=TO_DATE('".$mou_date."', 'DD/MM/YYYY') WHERE NPK_ID=".$npk_id;
		$q = $this->db->query($sql);
	}
	
	public function getPengantarBAAttr($npk_id, $cond="") {
	    $result = array();
		$sql = "SELECT * FROM BA WHERE NPK_ID=".$npk_id;
		if($cond!='') $sql .= " AND ".$cond;
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) $result = $q->result();
		return $result;
    }
	
	public function setPengantarBAAttr($npk_id, $am_name, $am_pos, $data_source
		, $ubc_signer_name, $ubc_signer_nik, $ubc_signer_pos, $gs_signer_name, $gs_signer_nik, $gs_signer_pos) {
		$sql = "SELECT * FROM BA WHERE NPK_ID=".$npk_id;
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) {
			$sql = "UPDATE BA SET AM_NAME='".$am_name."', AM_POS='".$am_pos."', ".
				" DATA_SOURCE='".$data_source."', UBC_SIGNER_NAME='".$ubc_signer_name."', ".
				" UBC_SIGNER_NIK='".$ubc_signer_nik."', UBC_SIGNER_POS='".$ubc_signer_pos."', ".
				" GS_SIGNER_NAME='".$gs_signer_name."', "." GS_SIGNER_NIK='".$gs_signer_nik."', ".
				" GS_SIGNER_POS='".$gs_signer_pos."' WHERE NPK_ID=".$npk_id;
		} else {
			$sql = "INSERT INTO BA(NPK_ID, AM_NAME, AM_POS, DATA_SOURCE, UBC_SIGNER_NAME, UBC_SIGNER_NIK, ".
				"UBC_SIGNER_POS, GS_SIGNER_NAME, GS_SIGNER_NIK, GS_SIGNER_POS) ".
				" VALUES(".$npk_id.", '".$am_name."', '".$am_pos."', '".$data_source."', ".
				"'".$ubc_signer_name."', '".$ubc_signer_nik."', '".$ubc_signer_pos."', ".
				"'".$gs_signer_name."', '".$gs_signer_nik."', '".$gs_signer_pos."')";
		}
		$qupd = $this->db->query($sql);
	}
    
}
?>