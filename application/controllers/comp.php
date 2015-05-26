<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class comp extends CI_Controller {
	
	private $filters;
	
	function __construct() {
		parent::__construct();
                if($this->session->userdata('d_user_id')=="" || $this->session->userdata('d_prof_id')=="") {
			if($this->session->userdata('d_user_id')=="")
				redirect("/home/login");
			elseif($this->session->userdata('d_prof_id')=="")
				redirect("/home/setprofile");
		}
                
		$this->load->model('M_profiling');
		$this->load->model('M_compfee');
	}
	
	private function filtering() {
		if(isset($_POST["filter"]) ) {
			$this->session->set_userdata('d_filter', $_POST);
			$this->filters = $_POST;
		} elseif($this->session->userdata('d_filter')!="") {
			$this->filters = $this->session->userdata('d_filter');
			$_POST = $this->session->userdata('d_filter');
		}
	}

	public function type() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_cf";
		$pm["mid-content"]["dt"] = $this->M_compfee->getLists("CF_TYPE<>'SDEF'");
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function typeadd() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_cf_add";
		$this->load->view('v_body', array("ct"=>$ct) );
	}
	
	public function typeadddo() {
		if(isset($_POST["submit"])) {
			$type = $this->M_compfee->getLists("CF_NAME='".strtoupper($_POST['cf_name'])."'");
			if( !(count($type) > 0)) {
				$this->M_compfee->insert(strtoupper($_POST['cf_name']), $_POST['cf_type'], $_POST['line_type'], strtoupper(trim($_POST['str_formula'])), $_POST['cf_caption']);
			}
		}
		redirect("/comp/type");
	}
	
	public function typeedit($cf_id) {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_cf_edit";
		$pm["mid-content"]["dt"] = $this->M_compfee->getLists("CF_ID=".$cf_id);
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function typeeditdo() {
		if(isset($_POST["submit"])) {
			$this->M_compfee->update($_POST['cf_id'], strtoupper($_POST['cf_name']), $_POST['cf_type'], $_POST['line_type'], strtoupper(trim($_POST['str_formula'])), $_POST['cf_caption']);
		}
		redirect("/comp/type");
	}
	
	public function typedel($cf_id) {
		$this->M_compfee->remove($cf_id);
		redirect("/comp/type");
	}
	
	public function tier() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_tier";
		$pm["mid-content"]["dt"] = $this->M_compfee->getTier();
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function tieradd() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_tier_add";
		$this->load->view('v_body', array("ct"=>$ct) );
	}
	
	public function tieradddo() {
		if(isset($_POST["submit"])) {
			$tier = $this->M_compfee->getTier("TIER_NAME='".strtoupper($_POST['tier_name'])."'");
			if( !(count($tier) > 0)) {
				$this->M_compfee->insertTier(strtoupper($_POST['tier_name']), $_POST['tier_params'], $_POST['tier_desc']);
			}
		}
		redirect("/comp/tier");
	}
	
	public function tieredit($tier_id) {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_tier_edit";
		$pm["mid-content"]["dt"] = $this->M_compfee->getTier("TIER_ID=".$tier_id);
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function tiereditdo() {
		if(isset($_POST["submit"])) {
			$this->M_compfee->updateTier($_POST['tier_id'], strtoupper($_POST['tier_name']), $_POST['tier_params'], $_POST['tier_desc']);
		}
		redirect("/comp/tier");
	}
	
	public function tierdel($tier_id) {
		$this->M_compfee->removeTier($tier_id);
		redirect("/comp/tier");
	}
	
	public function tiercond($tier_id) {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_tiercond";
		$pm["mid-content"]["tier"] = $this->M_compfee->getTier("TIER_ID=".$tier_id);
		$pm["mid-content"]["dt"] = $this->M_compfee->getTierCond("TIER_ID=".$tier_id);
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function tiercondadd($tier_id) {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_tiercond_add";
		$pm["mid-content"]["tier"] = $this->M_compfee->getTier("TIER_ID=".$tier_id);
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function tiercondadddo($tier_id) {
		if(isset($_POST["submit"])) {
			$cond = $this->M_compfee->getTierCond("TIER_ID=".$tier_id." AND SEQ_NO=".$_POST['seq_no']);
			if( !(count($cond) > 0)) {
				$this->M_compfee->insertTierCond($_POST['tier_id'], $_POST['seq_no'], $_POST['str_cond'], $_POST['nresult']);
			}
		}
		redirect("/comp/tiercond/".$tier_id);
	}
	
	public function tiercondedit($tier_id, $seq_no) {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_tiercond_edit";
		$pm["mid-content"]["tier"] = $this->M_compfee->getTier("TIER_ID=".$tier_id);
		$pm["mid-content"]["dt"] = $this->M_compfee->getTierCond("TIER_ID=".$tier_id." AND SEQ_NO=".$seq_no);
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function tiercondeditdo($tier_id) {
		if(isset($_POST["submit"])) {
			$this->M_compfee->updateTierCond($_POST['tier_id'], $_POST['seq_no'], $_POST['str_cond'], $_POST['nresult']);
		}
		redirect("/comp/tiercond/".$tier_id);
	}
	
	public function tierconddel($tier_id, $seq_no) {
		$this->M_compfee->removeTierCond($tier_id, $seq_no);
		redirect("/comp/tiercond/".$tier_id);
	}
        
        public function manual() {
            $this->load->model('M_tenant');
            $this->load->model('M_pengelola');
            $this->load->view('v_head');
            $ct["mid-menu"] = "v_menu";
            $ct["mid-content"] = "v_cf_man";
            $pm["mid-content"]["pgl"] = array();
            foreach($this->M_pengelola->getLists() as $k => $v) {
                    $pm["mid-content"]["pgl"][$v->PGL_ID] = $v->PGL_NAME;
            }
            $this->filtering();
            if(count($this->filters)>1 
		&& isset($this->filters["f_pgl_id"])  ) {
                    $pm["mid-content"]["dt"] = $this->M_tenant->getTenUsageStatis($this->filters["f_pgl_id"]);
            } else {
                $pm["mid-content"]["dt"] = $this->M_tenant->getTenUsageStatis();
            }
            
            $this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function manualadd() {
            $this->load->model('M_tenant');
            $this->load->model('M_pengelola');
            $this->load->model('M_compfee');
            $this->load->view('v_head');
            $ct["mid-menu"] = "v_menu";
            $ct["mid-content"] = "v_cf_man_add";
            $pm["mid-content"]["pgl"] = array();
            foreach($this->M_pengelola->getLists() as $k => $v) {
                    $pm["mid-content"]["pgl"][$v->PGL_ID] = $v->PGL_NAME;
            }
            $pm["mid-content"]["cf"] = array();
            foreach($this->M_compfee->getLists("CF_TYPE<>'SDEF'") as $k => $v) {
                    $pm["mid-content"]["cf"][$v->CF_ID] = $v->CF_NAME;
            }
            $this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function manualadddo() {
            $this->load->model('M_tenant');
            if(isset($_POST["submit"])) {
                    $type = $this->M_tenant->getTenUsageStatis("", $_POST['ten_id'], $_POST['cf_id']);
                    if( !(count($type) > 0)) {
                            $this->M_tenant->insertTenUsageStatis($_POST['ten_id'], $_POST['cf_id'], $_POST['cf_nom']);
                    }
            }
            redirect("/comp/manual");
	}
	
	public function manualedit($pgl_id, $ten_id, $cf_id) {
            $this->load->model('M_tenant');
            $this->load->model('M_pengelola');
            $this->load->model('M_compfee');
            $this->load->view('v_head');
            $ct["mid-menu"] = "v_menu";
            $ct["mid-content"] = "v_cf_man_edit";
            $pm["mid-content"]["dt"] = $this->M_tenant->getTenUsageStatis("", $ten_id, $cf_id);
            $pm["mid-content"]["cf"] = array();
            foreach($this->M_compfee->getLists("CF_TYPE<>'SDEF'") as $k => $v) {
                    $pm["mid-content"]["cf"][$v->CF_ID] = $v->CF_NAME;
            }
            
            $this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function manualeditdo() {
            $this->load->model('M_tenant');
            if(isset($_POST["submit"])) {
                    $this->M_tenant->updateTenUsageStatis($_POST['ten_id'], $_POST['cf_id'], $_POST['cf_nom']);
            }
            redirect("/comp/manual");
	}
	
	public function manualdel($ten_id, $cf_id) {
                $this->load->model('M_tenant');
		$this->M_tenant->removeTenUsageStatis($ten_id, $cf_id);
		redirect("/comp/manual");
	}

}

?>