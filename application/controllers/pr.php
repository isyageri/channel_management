<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class pr extends CI_Controller {
	
	function __construct() {
		parent::__construct();
                if($this->session->userdata('d_user_id')=="" || $this->session->userdata('d_prof_id')=="") {
			if($this->session->userdata('d_user_id')=="")
				redirect("/home/login");
			elseif($this->session->userdata('d_prof_id')=="")
				redirect("/home/setprofile");
		}
                
		$this->load->model('M_profiling');
		$this->load->model('M_user');
	}

	// Profile
	public function profile() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_profile";
		$pm["mid-content"]["dt"] = $this->M_profiling->getLists();
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function profileadd() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_profile_add";
		$pm["mid-content"]["dt"] = array();
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function profileadddo() {
		if(isset($_POST["submit"])) {
			$this->M_profiling->insert($_POST["prof_name"], $_POST["prof_desc"]);
		}
		redirect("/pr/profile");
	}
	
	public function profileedit($prof_id) {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_profile_edit";
		$pm["mid-content"]["dt"] = $this->M_profiling->getLists("PROF_ID=".$prof_id);
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function profileeditdo() {
		if(isset($_POST["submit"])) {
			$this->M_profiling->update($_POST["prof_id"], $_POST["prof_name"], $_POST["prof_desc"]);
		}
		redirect("/pr/profile");
	}
	
	public function profiledel($prof_id) {
		$this->M_profiling->remove($prof_id);
		redirect("/pr/profile");
	}
	
	// Menu
	public function menu() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_menulist";
		$pm["mid-content"]["dt"] = $this->M_profiling->getMenuAll("MENU_PARENT=0");
		$pm["mid-content"]["parent"] = 0;
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function menuchild($parent) {
		if($parent==0) {
			redirect("/pr/menu");
		} else {
			$this->load->view('v_head');
			$ct["mid-menu"] = "v_menu";
			$ct["mid-content"] = "v_menulist";
			$pm["mid-content"]["dt"] = $this->M_profiling->getMenuAll("MENU_PARENT=".$parent);
			$pm["mid-content"]["parent"] = $parent;
			$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
		}
	}
	
	public function menuadd($parent) {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_menulist_add";
		$pm["mid-content"]["dt"] = array();
		$pm["mid-content"]["parent"] = $parent;
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function menuadddo() {
		if(isset($_POST["submit"])) {
			$menu_id = $this->M_profiling->menuInsert($_POST['menu_name'], "", "", $_POST['menu_link']);
			$this->M_profiling->setMenuParent($menu_id, $_POST['menu_parent']);
		}
		redirect("/pr/menuchild/".$_POST['menu_parent']);
	}
	
	public function menuedit($menu_id) {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_menulist_edit";
		$pm["mid-content"]["dt"] = $this->M_profiling->getMenuAll("MENU_ID=".$menu_id);
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function menueditdo() {
		if(isset($_POST["submit"])) {
			$this->M_profiling->menuUpdate($_POST['menu_id'], $_POST['menu_name'], "", "", $_POST['menu_link']);
		}
		redirect("/pr/menuchild/".$_POST['menu_parent']);
	}
	
	public function menudel($menu_id) {
		$this->M_profiling->menuRemove($menu_id);
		redirect("/pr/menu");
	}
	
	public function menuacc($prof_id) {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_menulist_access";
		$pm["mid-content"]["dt"] = $this->M_profiling->getMenuAll("MENU_PARENT=0");
		$pm["mid-content"]["parent"] = 0;
		$pm["mid-content"]["prof"] = $this->M_profiling->getLists("PROF_ID=".$prof_id);
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function menuchildacc($prof_id, $parent) {
		if($parent==0) {
			redirect("/pr/menuacc/".$prof_id);
		} else {
			$this->load->view('v_head');
			$ct["mid-menu"] = "v_menu";
			$ct["mid-content"] = "v_menulist_access";
			$pm["mid-content"]["dt"] = $this->M_profiling->getMenuAll("MENU_PARENT=".$parent);
			$pm["mid-content"]["parent"] = $parent;
			$pm["mid-content"]["prof"] = $this->M_profiling->getLists("PROF_ID=".$prof_id);
			$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
		}
	}
	
	public function setmenuacc() {
		if(strtoupper($_POST['is_checked'])=="TRUE") {
			$this->M_profiling->assignMenu($_POST['menu_id'], $_POST['prof_id']);
		} else {
			$this->M_profiling->unassignMenu($_POST['menu_id'], $_POST['prof_id']);
		}
		redirect("/pr/menuchildacc/".$_POST['prof_id']."/".$_POST['parent']);
	}
	
	// User
	public function user() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_user";
		$pm["mid-content"]["dt"] = $this->M_user->getLists();
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function useradd() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_user_add";
		$pm["mid-content"]["dt"] = array();
		$pm["mid-content"]["prof"] = array();
		foreach($this->M_profiling->getLists() as $k => $v) {
			$pm["mid-content"]["prof"][$v->PROF_ID] = $v->PROF_NAME;
		}
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function useradddo() {
		print_r($_POST);
		if(isset($_POST["submit"])) {
			$user = $this->M_user->getLists("NIK='".$_POST['nik']."'");
			if( !(count($user) > 0)) {
				$user_id = $this->M_user->insert($_POST['nik'], $_POST['user_name'], $_POST['email'], $_POST['loker'], $_POST['addr_street'], $_POST['addr_city'], $_POST['contact_no']);
				if(isset($_POST["prof_id"])) {
					foreach($_POST["prof_id"] as $k => $v) {
						$this->M_user->setProfile($user_id, $v);
					}
				}
				
				if($_POST["passwd"]==$_POST["confirm_passwd"] && $_POST["passwd"]!="") {
					$this->M_user->setPassword($user_id, $_POST["passwd"]);
				} elseif($_POST["passwd"]!=$_POST["confirm_passwd"]) {
					redirect("/pr/usereditwrong/".$user_id."/1");
				} elseif($_POST["passwd"]=="") {
					redirect("/pr/usereditwrong/".$user_id."/2");
				} 
			}
			redirect("/pr/user");
		} else {
			redirect("/pr/user");
		}
		
	}
	
	public function usereditwrong($user_id, $wrong) {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_user_edit";
		$pm["mid-content"]["dt"] = $this->M_user->getLists("USER_ID=".$user_id);
		$pm["mid-content"]["prof"] = array();
		if($wrong==1) 
			$pm["mid-content"]["warning"] = "Your password doesn't match with password confirmation.";
		elseif($wrong==2) 
			$pm["mid-content"]["warning"] = "You don't set password.";
		$pm["mid-content"]["prof_select"] = $this->M_user->getProfile($user_id);
		foreach($this->M_profiling->getLists() as $k => $v) {
			$pm["mid-content"]["prof"][$v->PROF_ID] = $v->PROF_NAME;
		}
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function useredit($user_id) {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_user_edit";
		$pm["mid-content"]["dt"] = $this->M_user->getLists("USER_ID=".$user_id);
		$pm["mid-content"]["prof"] = array();
		$pm["mid-content"]["prof_select"] = $this->M_user->getProfile($user_id);
		foreach($this->M_profiling->getLists() as $k => $v) {
			$pm["mid-content"]["prof"][$v->PROF_ID] = $v->PROF_NAME;
		}
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function usereditdo() {
		if(isset($_POST["submit"])) {
			$this->M_user->update($_POST['user_id'], $_POST['nik'], $_POST['user_name'], $_POST['email'], $_POST['loker'], 
				$_POST['addr_street'], $_POST['addr_city'], $_POST['contact_no']);
			if(isset($_POST["prof_id"])) {
				$this->M_user->clearProfile($_POST['user_id']);
				foreach($_POST["prof_id"] as $k => $v) {
					$this->M_user->setProfile($_POST['user_id'], $v);
				}
			}
			if($_POST["passwd"]==$_POST["confirm_passwd"] && $_POST["passwd"]!="") {
				$this->M_user->setPassword($_POST['user_id'], $_POST["passwd"]);
			} elseif($_POST["passwd"]!=$_POST["confirm_passwd"]) {
				redirect("/pr/usereditwrong/".$_POST['user_id']."/1");
			}
			redirect("/pr/user");
		} else {
			redirect("/pr/user");
		}
	}
	
	public function userdel($user_id) {
		$this->M_user->remove($user_id);
		$this->M_user->clearProfile($user_id);
		redirect("/pr/user");
	}
	
	public function userc2bi() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_user_c2bi";
		$pm["mid-content"]["dt"] = $this->M_user->getC2BiLists();
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function userc2biedit($user_id) {
		$this->load->model('M_pengelola');
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_user_c2bi_edit";
		$pm["mid-content"]["dt"] = $this->M_user->getC2BiLists("A.USER_ID=".$user_id);
		$pm["mid-content"]["pgl"] = $this->M_pengelola->getLists();
		$pm["mid-content"]["pgl_select"] = $this->M_user->getPgl($user_id);
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function userc2bieditdo() {
		if(isset($_POST["submit"])) {
			if( count($this->M_user->getPgl($_POST['user_id'], "A.PGL_ID=".$_POST['pgl_id']))==0 )
				$this->M_user->setProfilePgl($_POST['user_id'], $_POST['pgl_id']);
			redirect("/pr/userc2biedit/".$_POST['user_id']);
		} else {
			redirect("/pr/userc2bi");
		}
	}
	
	public function userc2bidel($user_id, $pgl_id) {
		$this->M_user->removeProfilePgl($user_id, $pgl_id);
		redirect("/pr/userc2biedit/".$user_id);
	}
	
	public function myprof($wrong=0) {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_myprofile";
		$pm["mid-content"]["dt"] = $this->M_user->getLists("USER_ID=".$this->session->userdata("d_user_id"));
		$pm["mid-content"]["prof"] = array();
		$pm["mid-content"]["prof_select"] = $this->M_user->getProfile($this->session->userdata("d_user_id"));
		foreach($this->M_profiling->getLists() as $k => $v) {
			$pm["mid-content"]["prof"][$v->PROF_ID] = $v->PROF_NAME;
		}
		if($wrong==1) 
			$pm["mid-content"]["warning"] = "Your password doesn't match with password confirmation.";
		elseif($wrong==2) 
			$pm["mid-content"]["warning"] = "You don't set password.";
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function myprofdo() {
		if(isset($_POST["submit"])) {
			$this->M_user->update($_POST['user_id'], $_POST['nik'], $_POST['user_name'], $_POST['email'], $_POST['loker'], 
				$_POST['addr_street'], $_POST['addr_city'], $_POST['contact_no']);
			if($_POST["passwd"]==$_POST["confirm_passwd"] && $_POST["passwd"]!="") {
				$this->M_user->setPassword($_POST['user_id'], $_POST["passwd"]);
			} elseif($_POST["passwd"]!=$_POST["confirm_passwd"]) {
				redirect("/pr/myprof/1");
			}
			redirect("/home");
		} else {
			redirect("/home");
		}
	}
	
}

?>