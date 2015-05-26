<?php
class M_param extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    public function getValue($p_name) {
		$sql = "select * from app_params where p_name='".$p_name."'";
		$q = $this->db->query($sql); 
		if($q->num_rows() > 0) {
			foreach($q->result() as $k => $v) {
				if($v->p_type=="STRING") return $v->p_value;
				else {
					$result = array();
					$val = substr($v->p_value, 1, strlen($v->p_value)-2 );
					$val = explode(",", $val);
					foreach($val as $ks => $s) {
						$tmp = str_replace("://","[url_sign]",$s);
						$val2 = explode(":", $tmp);
						if(count($val2)==2) {
							$result[$val2[0]] = trim( str_replace("[url_sign]","://",$val2[1]) );
						} else {
							array_push($result, trim($val2[0]));
						}
					}
					return $result;
				}
				break;
			}
		}
    }
    
    public function getEmailConfig() {
		return $this->getValue("p_email_config");
    }
    
    public function getEmailSender() {
	    return $this->getValue("p_email_sender");
    }
    
    public function getParamProducts() {
        return array('1'=>'POTS', '11'=>'SPEEDY', '9'=>'FLEXI');
    }
    
    
}
?>