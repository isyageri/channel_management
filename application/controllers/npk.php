<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class npk extends CI_Controller {
	
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
		$this->load->model('M_npk');
		$this->load->model('M_pengelola');
		$this->filters = array();
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

	public function draft() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_npkdraft";
		$cond = "";
		$this->filtering();
		if(count($this->filters)>1 
		&& isset($this->filters["f_pgl_id"]) 
		&& isset($this->filters["f_period_m"]) 
		&& isset($this->filters["f_period_y"]) ) {
			if($this->filters["f_pgl_id"]!="") $cond .= " AND PGL_ID=".$this->filters["f_pgl_id"];
			if($this->filters["f_period_m"]!="" && $this->filters["f_period_y"]!="") $cond .= " AND PERIOD='".$this->filters["f_period_y"].$this->filters["f_period_m"]."'";
		}
		$pm["mid-content"]["dt"] = $this->M_npk->getLists("STATUS=1".$cond);
		foreach($this->M_pengelola->getLists("ENABLE_FEE='1'") as $k => $v) 
			$pm["mid-content"]["pgl"][$v->PGL_ID] = $v->PGL_NAME;
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function draftadd() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_npkdraft_add";
		foreach($this->M_pengelola->getLists("ENABLE_FEE='1'") as $k => $v) 
			$pm["mid-content"]["pgl"][$v->PGL_ID] = $v->PGL_NAME;
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	/*
	public function draftadddo() {
		if(isset($_POST["submit"])) {
			$period = $_POST['period_y'].$_POST['period_m'];
			$type = $this->M_npk->getLists("PGL_ID=".$_POST['pgl_id']." AND PERIOD='".$period."'"); 
			if( !(count($type) > 0)) {
				if($this->M_npk->isValidNPK($_POST['pgl_id'], $period)) {
					$this->M_npk->insert($_POST['pgl_id'], $period, $_POST['method'], date("d/m/Y"), "0", $_POST['sign_name_1'], $_POST['sign_pos_1'], $_POST['sign_name_2'], $_POST['sign_pos_2']);
				} else {
					echo "PKS expired";
				}
			}
		}
		redirect("/npk/draft");
	}
	*/
	public function draftadddo() {
		if(isset($_POST["submit"])) {
			$period = $_POST['period_y'].$_POST['period_m'];
			$type = $this->M_npk->getLists("PGL_ID=".$_POST['pgl_id']." AND PERIOD='".$period."'"); 
			if( !(count($type) > 0)) {
				$this->M_npk->insert($_POST['pgl_id'], $period, $_POST['method'], date("d/m/Y"), "0", $_POST['sign_name_1'], $_POST['sign_pos_1'], $_POST['sign_name_2'], $_POST['sign_pos_2']);
			}
		}
		redirect("/npk/draft");
	}
	
	public function draftedit($npk_id) {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_npkdraft_edit";
		$pm["mid-content"]["dt"] = $this->M_npk->getLists("NPK_ID=".$npk_id);
		foreach($this->M_pengelola->getLists() as $k => $v) 
			$pm["mid-content"]["pgl"][$v->PGL_ID] = $v->PGL_NAME;
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function drafteditdo() {
		if(isset($_POST["submit"])) {
			$this->M_npk->update($_POST['npk_id'], $_POST['pgl_id'], $_POST['period'], $_POST['method'], date("d/m/Y"), "0", $_POST['sign_name_1'], $_POST['sign_pos_1'], $_POST['sign_name_2'], $_POST['sign_pos_2']);
		}
		redirect("/npk/draft");
	}
	
	public function draftdel($npk_id) {
		$this->M_npk->remove($npk_id);
		redirect("/npk/draft");
	}
	
	public function lock($npk_id) {
		$this->M_npk->setStatus($npk_id, 9);
		redirect("/calc/step");
	}
	
	public function unlock($npk_id) {
		$this->M_npk->setStatus($npk_id, 3);
		redirect("/calc/locked");
	}

	public function save($npk_id) {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_npksave";
		$pm["mid-content"]["dt"] = $this->M_npk->getLists("NPK_ID=".$npk_id);
		$pm["mid-content"]["pgl"] = $this->M_pengelola->getLists("PGL_ID=".$pm["mid-content"]["dt"][0]->PGL_ID);
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}

	public function savedo($npk_id) {
		if(isset($_POST["submit"])) {
			$rc = $this->M_npk->getRecent("NPK_RC_NAME='".$_POST['npk_rc_name']."'"); 
			if( !(count($rc) > 0)) {
				$this->M_npk->saveTo($npk_id, $_POST['npk_rc_name']);
			}
		}
		redirect("/doc/npk");
	}

	public function loadfml($npk_id) {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_npkloadfml";
		$pm["mid-content"]["dt"] = $this->M_npk->getLists("NPK_ID=".$npk_id);
		$pm["mid-content"]["pgl"] = $this->M_pengelola->getLists("PGL_ID=".$pm["mid-content"]["dt"][0]->PGL_ID);
		foreach($this->M_npk->getRecent() as $k=>$v) 
			$pm["mid-content"]["rc"][$v->NPK_RC_ID] = $v->NPK_RC_NAME;
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}

	public function loadfmldo($npk_id) {
		if(isset($_POST["submit"])) {
			$this->M_npk->loadFormula($_POST['npk_rc_id'], $_POST['npk_id']);
		}
		redirect("/calc/tostep1/".$npk_id);
	}

}

?>