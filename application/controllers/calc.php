<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class calc extends CI_Controller {
	
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
		$this->load->model('M_pengelola');
		$this->load->model('M_npk');
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

	public function index() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_pgl";
		$pm["mid-content"]["dt"] = $this->M_profiling->getLists();
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function process() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_process_dummy";
		$pm["mid-content"]["dt"] = $this->M_profiling->getLists();
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function step() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_calcstep";
		$cond = "";
		$this->filtering();
		if(count($this->filters)>1 
		&& isset($this->filters["f_pgl_id"]) 
		&& isset($this->filters["f_period_m"]) 
		&& isset($this->filters["f_period_y"]) ) {
			if($this->filters["f_pgl_id"]!="") $cond .= " AND PGL_ID=".$this->filters["f_pgl_id"];
			if($this->filters["f_period_m"]!="" && $this->filters["f_period_y"]!="") $cond .= " AND PERIOD='".$this->filters["f_period_y"].$this->filters["f_period_m"]."'";
		}
		$pm["mid-content"]["dt"] = $this->M_npk->getLists("STATUS IN(1,2,3,4,5,6,7)".$cond);
		foreach($this->M_pengelola->getLists("ENABLE_FEE='1'") as $k => $v) 
			$pm["mid-content"]["pgl"][$v->PGL_ID] = $v->PGL_NAME;
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function tostep0($npk_id) {
		$this->M_npk->step0($npk_id);
		redirect("/calc/tostep1/".$npk_id);
	}
	
	public function tostep1($npk_id) {
		$this->load->model("M_compfee");
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_calcstepdo_1";
		$pm["mid-content"]["dt"] = $this->M_npk->getLists("NPK_ID=".$npk_id);
		$pm["mid-content"]["pgl"] = $this->M_pengelola->getLists("PGL_ID=".$pm["mid-content"]["dt"][0]->PGL_ID);
		$pm["mid-content"]["proc"] = $this->M_npk->getProcess($npk_id, 0);
		$pm["mid-content"]["proc_exist"] = $this->M_npk->getProcess($npk_id, 1);
		$pm["mid-content"]["tier"] = $this->M_compfee->getTier();
		$pm["mid-content"]["udef"] = $this->M_compfee->getLists("CF_TYPE='UDEF'");
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function tostep1do($npk_id) {
		$this->load->model('M_compfee');
		if(isset($_POST["submit"])) {
			if( isset($_POST["str_formula"])) {
				//$this->M_npk->removeProcess($_POST['npk_id'], $_POST['step']); // don't remove again
				foreach($_POST["str_formula"] as $k => $v) {
					if(trim($v)!="") {
						$cf_id = $this->M_compfee->insertSys($v);
						$this->M_npk->insertProcess($_POST['npk_id'], $_POST['step'], $cf_id, 0, $v);
						
						// Calculate
						$cf_nom = $this->M_npk->parseFormula($_POST['npk_id'], $v);
						$this->M_npk->updateNom($_POST['npk_id'], $_POST['step'], $v, $cf_nom);
					}
				}
				$this->M_npk->setStatus($npk_id, 2);
			}
			if( isset($_POST["str_formula_exist"])) {
				foreach($_POST["str_formula_exist"] as $k => $v) {
					if(trim($v)!="") {
						$cf_id = $k;
						$this->M_compfee->updateStrFormula($cf_id, $v);
						$this->M_npk->updateProcess($_POST['npk_id'], $_POST['step'], $cf_id, 0, $v);
						
						// Calculate
						$cf_nom = $this->M_npk->parseFormula($_POST['npk_id'], $v);
						$this->M_npk->updateNom($_POST['npk_id'], $_POST['step'], $v, $cf_nom);
					} else {
						$cf_id = $k;
						$this->M_npk->removeProcessCom($_POST['npk_id'], $_POST['step'], $cf_id);
					}
				}
				$this->M_npk->setStatus($npk_id, 2);
			}
			redirect("/calc/tostep1/".$npk_id);
		} elseif(isset($_POST["calculate"])) {
			if( isset($_POST["str_formula"]) ) {
				foreach($_POST["str_formula"] as $k => $v) {
					if(trim($v)!="") {
						$cf_nom = $this->M_npk->parseFormula($npk_id, $v);
						$this->M_npk->updateNom($npk_id, $_POST['step'], $v, $cf_nom);
					}
				}
				$this->M_npk->setStatus($npk_id, 2);
			}
			redirect("/calc/tostep1/".$npk_id);
		} elseif(isset($_POST["next"])) {
			redirect("/calc/tostepn/".$npk_id."/2");
		}
		
	}
	
	public function tostepn($npk_id, $step_n) {
		$this->load->model("M_compfee");
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_calcstepdo_n";
		$pm["mid-content"]["dt"] = $this->M_npk->getLists("NPK_ID=".$npk_id);
		$pm["mid-content"]["pgl"] = $this->M_pengelola->getLists("PGL_ID=".$pm["mid-content"]["dt"][0]->PGL_ID);
		$pm["mid-content"]["proc"] = $this->M_npk->getProcess($npk_id, $step_n-1);
		$pm["mid-content"]["proc_exist"] = $this->M_npk->getProcess($npk_id, $step_n);
		$pm["mid-content"]["tier"] = $this->M_compfee->getTier();
		$pm["mid-content"]["udef"] = $this->M_compfee->getLists("CF_TYPE='UDEF'");
		$pm["mid-content"]["step_n"] = $step_n;
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function tostepndo($npk_id) {
		$this->load->model('M_compfee');
		//echo "<pre>"; print_r($_POST); echo "</pre>"; 
		if(isset($_POST["submit"])) {
			if( isset($_POST["str_formula"])) {
				//$this->M_npk->removeProcess($_POST['npk_id'], $_POST['step']); // don't remove again
				foreach($_POST["str_formula"] as $k => $v) {
					if(trim($v)!="") {
						$cf_id = $this->M_compfee->insertSys($v);
						$this->M_npk->insertProcess($_POST['npk_id'], $_POST['step'], $cf_id, 0, $v);
						
						// Calculate 
						$cf_nom = $this->M_npk->parseFormula($_POST['npk_id'], $v);
						$this->M_npk->updateNom($_POST['npk_id'], $_POST['step'], $v, $cf_nom);
					}
				}
				$this->M_npk->setStatus($npk_id, 2);
			}
			if( isset($_POST["str_formula_exist"])) {
				foreach($_POST["str_formula_exist"] as $k => $v) {
					if(trim($v)!="") {
						$cf_id = $k;
						$this->M_compfee->updateStrFormula($cf_id, $v);
						$this->M_npk->updateProcess($_POST['npk_id'], $_POST['step'], $cf_id, 0, $v);
						
						// Calculate
						$cf_nom = $this->M_npk->parseFormula($_POST['npk_id'], $v);
						$this->M_npk->updateNom($_POST['npk_id'], $_POST['step'], $v, $cf_nom);
					} else {
						$cf_id = $k;
						$this->M_npk->removeProcessCom($_POST['npk_id'], $_POST['step'], $cf_id);
					}
				}
				$this->M_npk->setStatus($npk_id, 2);
			}
			redirect("/calc/tostepn/".$npk_id."/".$_POST['step']);
		} elseif(isset($_POST["calculate"])) {
			if( isset($_POST["str_formula"]) ) {
				foreach($_POST["str_formula"] as $k => $v) {
					if(trim($v)!="") {
						$cf_nom = $this->M_npk->parseFormula($npk_id, $v);
						$this->M_npk->updateNom($npk_id, $_POST['step'], $v, $cf_nom);
					}
				}
				$this->M_npk->setStatus($npk_id, 2);
			}
			redirect("/calc/tostepn/".$npk_id."/".$_POST['step']);
		} elseif(isset($_POST["next"])) {
			redirect("/calc/tostepn/".$npk_id."/".($_POST['step']+1));
		} elseif(isset($_POST["prev"])) {
			if($_POST['step']==2)
				redirect("/calc/tostep1/".$npk_id);
			else
				redirect("/calc/tostepn/".$npk_id."/".($_POST['step']-1));
		}
		
	}
	
	// Manual
	public function stepman() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_calcstepman";
		$cond = "";
		$this->filtering();
		if(count($this->filters)>1 
		&& isset($this->filters["f_pgl_id"]) 
		&& isset($this->filters["f_period_m"]) 
		&& isset($this->filters["f_period_y"]) ) {
			if($this->filters["f_pgl_id"]!="") $cond .= " AND PGL_ID=".$this->filters["f_pgl_id"];
			if($this->filters["f_period_m"]!="" && $this->filters["f_period_y"]!="") $cond .= " AND PERIOD='".$this->filters["f_period_y"].$this->filters["f_period_m"]."'";
		}
		$pm["mid-content"]["dt"] = $this->M_npk->getLists("STATUS IN(1,2,3,4,5,6,7)".$cond);
		foreach($this->M_pengelola->getLists("ENABLE_FEE='1'") as $k => $v) 
			$pm["mid-content"]["pgl"][$v->PGL_ID] = $v->PGL_NAME;
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function compdel($npk_id, $cf_id) {
		$this->M_npk->removeProcessCom($npk_id, 0, $cf_id);
		redirect("/calc/tostep1/".$npk_id);
	}
	
	public function addcomp0($npk_id) {
		$this->load->model("M_compfee");
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_calc_comp0_add";
		$pm["mid-content"]["npk_id"] = $npk_id;
		$pm["mid-content"]["dt"] = $this->M_npk->getLists("NPK_ID=".$npk_id);
		$pm["mid-content"]["pgl"] = $this->M_pengelola->getLists("PGL_ID=".$pm["mid-content"]["dt"][0]->PGL_ID);
		foreach($this->M_compfee->getLists("CF_TYPE='ORIG'") as $k => $v) 
			$pm["mid-content"]["compfee"][$v->CF_ID] = $v->CF_NAME;
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function addcomp0do($npk_id) {
		if(isset($_POST["submit"])) {
			$comp = $this->M_npk->getProcessCom($npk_id, 0, $_POST['cf_id']);
			if( !(count($comp) > 0)) {
				$this->load->model("M_compfee");
				$str0 = $this->M_compfee->getLists("CF_ID=".$_POST['cf_id']);
				$str0 = $str0[0]->STR_FORMULA;
				$this->M_npk->insertProcess($_POST['npk_id'], 0, $_POST['cf_id'], $_POST['cf_nom'], $str0);
			}
		}
		redirect("/calc/tostep1/".$npk_id);
	}
	
	public function refresh0($npk_id) {
		$this->M_npk->absolutelystep0($npk_id);
		redirect("/calc/step");
	}
	
	public function setasfee($npk_id, $step, $cf_id) {
		$this->M_npk->setCFAsFee($npk_id, $cf_id); 
		$this->M_npk->setNettoFee($npk_id);
		$this->M_npk->setStatus($npk_id, 3);
		if($step==1) 
			redirect("/calc/tostep1/".$npk_id);
		elseif($step>1)
			redirect("/calc/tostepn/".$npk_id."/".$step);
	}
	
	public function locked() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_calclocked";
		$cond = "";
		$this->filtering();
		if(count($this->filters)>1
		&& isset($this->filters["f_pgl_id"]) 
		&& isset($this->filters["f_period_m"]) 
		&& isset($this->filters["f_period_y"]) ) {
			if($this->filters["f_pgl_id"]!="") $cond .= " AND PGL_ID=".$this->filters["f_pgl_id"];
			if($this->filters["f_period_m"]!="" && $this->filters["f_period_y"]!="") $cond .= " AND PERIOD='".$this->filters["f_period_y"].$this->filters["f_period_m"]."'";
		}
		$pm["mid-content"]["dt"] = $this->M_npk->getLists("STATUS IN(9)".$cond);
		foreach($this->M_pengelola->getLists("ENABLE_FEE='1'") as $k => $v) 
			$pm["mid-content"]["pgl"][$v->PGL_ID] = $v->PGL_NAME;
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}

	
}
?>