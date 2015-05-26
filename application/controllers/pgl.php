<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class pgl extends CI_Controller {
	
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
		$cond = "";
		$this->filtering();
		if(count($this->filters)>1 
		&& isset($this->filters["f_pgl_name"]) ) {
			if($this->filters["f_pgl_name"]!="") $cond .= "LOWER(PGL_NAME) LIKE '%".strtolower($this->filters["f_pgl_name"])."%'";
		}
		$pm["mid-content"]["dt"] = $this->M_pengelola->getLists($cond);
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function pgladd() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_pgl_add";
		$this->load->view('v_body', array("ct"=>$ct) );
	}
	
	public function pgladddo() {
		if(isset($_POST["submit"])) {
			if(isset($_POST['enable_fee']) && $_POST['enable_fee']==1) $enable_fee=1;
			else $enable_fee=0;
			$this->M_pengelola->insert(0, $_POST['pgl_name'], $_POST['pgl_addr'], $_POST['pgl_contact_no'], $enable_fee);
		}
		redirect("/pgl");
	}
	
	public function pgledit($pgl_id) {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_pgl_edit";
		$pm["mid-content"]["dt"] = $this->M_pengelola->getLists("PGL_ID=".$pgl_id);
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function pgleditdo() {
		if(isset($_POST["submit"])) {
			if(isset($_POST['enable_fee']) && $_POST['enable_fee']==1) $enable_fee=1;
			else $enable_fee=0;
			$this->M_pengelola->update($_POST['pgl_id'], $_POST['pgl_name'], $_POST['pgl_addr'], $_POST['pgl_contact_no'], $enable_fee);
		}
		redirect("/pgl");
	}
	
	public function pgldel($pgl_id) {
		$this->M_pengelola->remove($pgl_id);
		redirect("/pgl");
	}
	
	public function mou($pgl_id) {
		$this->load->model('M_mou');
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_pgl_mou";
		$pm["mid-content"]["pgl"] = $this->M_pengelola->getLists("PGL_ID=".$pgl_id);
		$pm["mid-content"]["dt"] = $this->M_mou->getLists("A.PGL_ID=".$pgl_id);
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function mouadd($pgl_id) {
		$this->load->model('M_mou');
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_pgl_mou_add";
		$pm["mid-content"]["pgl"] = $this->M_pengelola->getLists("PGL_ID=".$pgl_id);
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function mouadddo() {
		$this->load->model('M_mou');
		if(isset($_POST["submit"])) {
			$this->M_mou->insert($_POST["mou_no"], $_POST["pgl_id"], $_POST["start_date"], $_POST["end_date"]);
		}
		redirect("/pgl/mou/".$_POST["pgl_id"]);
	}
	
	public function mouedit($pgl_id, $mou_no) {
		$this->load->model('M_mou');
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_pgl_mou_edit";
		$pm["mid-content"]["pgl"] = $this->M_pengelola->getLists("PGL_ID=".$pgl_id);
		$pm["mid-content"]["dt"] = $this->M_mou->getLists("MOU_NO='".str_replace("slash", "/", $mou_no)."'");
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function moueditdo() {
		$this->load->model('M_mou');
		if(isset($_POST["submit"])) {
			$this->M_mou->update($_POST["mou_no"], $_POST["pgl_id"], $_POST["start_date"], $_POST["end_date"]);
		}
		redirect("/pgl/mou/".$_POST["pgl_id"]);
	}
	
	public function moudel($pgl_id, $mou_no) {
		$this->load->model('M_mou');
		$this->M_mou->remove(str_replace("slash", "/", $mou_no));
		redirect("/pgl/mou/".$pgl_id);
	}
	
	public function amd($pgl_id, $mou_no) {
		$this->load->model('M_mou');
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_pgl_amd";
		$pm["mid-content"]["mou_no"] = str_replace("slash", "/", $mou_no);
		$pm["mid-content"]["pgl"] = $this->M_pengelola->getLists("PGL_ID=".$pgl_id);
		$pm["mid-content"]["dt"] = $this->M_mou->getAmdLists("MOU_NO='".str_replace("slash", "/", $mou_no)."'");
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function amdadd($pgl_id, $mou_no) {
		$this->load->model('M_mou');
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_pgl_amd_add";
		$pm["mid-content"]["mou_no"] = str_replace("slash", "/", $mou_no);
		$pm["mid-content"]["pgl"] = $this->M_pengelola->getLists("PGL_ID=".$pgl_id);
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function amdadddo() {
		$this->load->model('M_mou');
		if(isset($_POST["submit"])) {
			$this->M_mou->insertAmd($_POST["amd_no"], $_POST["mou_no"], $_POST["amd_date"], $_POST["amd_desc"]);
		}
		redirect("/pgl/amd/".$_POST["pgl_id"]."/".str_replace("/","slash",$_POST["mou_no"]));
	}
	
	public function amdedit($pgl_id, $mou_no, $amd_no) {
		$this->load->model('M_mou');
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_pgl_amd_edit";
		$pm["mid-content"]["mou_no"] = str_replace("slash", "/", $mou_no);
		$pm["mid-content"]["amd_no"] = str_replace("slash", "/", $amd_no);
		$pm["mid-content"]["pgl"] = $this->M_pengelola->getLists("PGL_ID=".$pgl_id);
		$pm["mid-content"]["dt"] = $this->M_mou->getAmdLists("AMD_NO='".str_replace("slash", "/", $amd_no)."'");
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function amdeditdo() {
		$this->load->model('M_mou');
		if(isset($_POST["submit"])) {
			$this->M_mou->updateAmd($_POST["amd_no"], $_POST["mou_no"], $_POST["amd_date"], $_POST["amd_desc"]);
		}
		redirect("/pgl/amd/".$_POST["pgl_id"]."/".str_replace("/", "slash", $_POST["mou_no"]) );
	}
	
	public function amddel($pgl_id, $mou_no, $amd_no) {
		$this->load->model('M_mou');
		$this->M_mou->removeAmd(str_replace("slash", "/", $amd_no));
		redirect("/pgl/amd/".$pgl_id."/".$mou_no);
	}
	
	public function ten($pgl_id) {
		$this->load->model('M_tenant');
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_pgl_ten";
		$pm["mid-content"]["dt"] = $this->M_tenant->getFromPgl($pgl_id);
		$pm["mid-content"]["pgl"] = $this->M_pengelola->getLists("PGL_ID=".$pgl_id);
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function tendel($pgl_id, $ten_id) {
		$this->M_pengelola->removeTenant($pgl_id, $ten_id);
		redirect("/pgl/ten/".$pgl_id);
	}

}

?>