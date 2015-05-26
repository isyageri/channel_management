<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	
	private $d_sub_menus;
	
	function __construct() {
		parent::__construct();
		$this->load->model('M_profiling');
		$this->load->model('M_user');
		$this->d_sub_menus = array();
       // $this->menu_nav = $this->session->userdata('menu_nav');
	}

	public function index()
	{
		//$this->session->sess_destroy();
		if($this->session->userdata('d_user_id')=="" || $this->session->userdata('d_prof_id')=="") {
			if($this->session->userdata('d_user_id')=="")
				redirect("login");
			elseif($this->session->userdata('d_prof_id')=="")
				redirect("/home/setprofile");
		} else {
			//$this->charts = '1';
			//$this->load->model('M_profiling');
			//$this->load->view('v_head');
			//$this->load->view('v_body', array('ct'=>array('mid-menu'=>'v_menu'), 'pm'=>array('mid-menu'=>array('x'=>'') ) ));
            //$this->session->set_userdata('menu_nav', 'home');
            $this->load->view('templates/header');
            $this->load->view('home/index');
            $this->load->view('templates/footer');

		}
	}
	
	public function login() {
		$this->load->view('user/v_login');
	}
	
	public function logindo() {
		$this->load->model('M_user');
		
		$this->load->library(array('encrypt', 'form_validation'));
		
		$this->form_validation->set_rules('user_name', 'username', 'required');
		$this->form_validation->set_rules('user_pass', 'password', 'required');
		$this->form_validation->set_error_delimiters('<em>','</em>');
		
		// has the form been submitted and with valid form info (not empty values)
		if(isset($_POST['login'])) {
			
			if($this->form_validation->run()) {
				$rc = $this->M_user->getLists("NIK='".$_POST['user_name']."' AND PASSWD='".md5($_POST['user_pass'])."'"); 
				//$rc = $this->M_user->getLists("NIK='".$_POST['user_name']."' AND PASSWD='".$_POST['user_pass']."'"); 
				if(count($rc) >=1) {
					print_r($_POST);
					$sessions = array(
					   'd_user_id'		=> $rc[0]->USER_ID,
					   'd_user_name'	=> $rc[0]->USER_NAME,
					   'd_nik'			=> $rc[0]->NIK,
					   'd_email'		=> $rc[0]->EMAIL
					);

					$this->session->set_userdata($sessions);
					
					$profs = $this->M_user->getUserProfile($rc[0]->USER_ID);
					if(count($profs)==0) {
						$this->session->set_userdata("d_user_id","");
						$this->session->set_userdata('d_message', "You don't have profile. <br>Please contact your administrator !");
					} elseif(count($profs)==1) {
						foreach($profs as $k => $v) {
							$prof_id = $k;
							$prof_name = $v;
						}
						$this->session->set_userdata('d_prof_id', $prof_id);
						$this->session->set_userdata('d_prof_name', $prof_name);
					} 
				} else {
					$rc = $this->M_user->getLists("NIK='".$_POST['user_name']."'"); 
					if(count($rc) > 0) {
						$this->session->set_userdata('d_message', "Password is wrong !");
					} else {
						$this->session->set_userdata('d_message', "A user doesn't exist !");
					}
				}
			}
		}
		
		redirect("/home");
	}

    public function nav($nav){
        $data['title'] = 'Channel Management';
        //die($nav);
        switch ($nav) {
            case 'm_mitra':
              //  $data['result'] = $this->dbfunction->getUserAproval();
                $this->load->view('channel_mgm/' . $nav);
                break;
            case 'deposit_confirm':
                $data['result'] = $this->dbfunction->getDepositList();
                $this->load->view('home/' . $nav, $data);
                break;
        }
    }
	
	public function setprofile() {
		$this->load->model("M_user");
		$profs = $this->M_user->getUserProfile($this->session->userdata("d_user_id"));
		$this->load->view('v_set_profile', array("profs"=>$profs));
	}
	
	public function setprofiledo() {
		if(isset($_POST['login'])) {
			$this->load->model("M_user");
			$profs = $this->M_user->getUserProfile($this->session->userdata("d_user_id"));
			$this->session->set_userdata('d_prof_id', $_POST['prof_id']);
			$this->session->set_userdata('d_prof_name', $profs[ $_POST['prof_id'] ]);
			
			// save menu to session
			$this->setmenus($_POST['prof_id']);
		}
		redirect("/home");
	}
	
	public function setmenus($prof_id) {
		$this->load->model('M_profiling');
		$d_menus = "";
		$d_menus .= "<ul class='sf-menu'>";
		$d_menus .= "<li><a href='".site_url("")."'><img src='".image_asset_url("home_icon.png")."' width='20' style='margin-top:-5px; margin-bottom:-5px;' /> Home</a>";
		$d_menus .= "</li>";
		foreach($this->M_profiling->getMenuByProf($prof_id,0) as $k => $v) {
			$d_menus .= "<li><a href='".site_url($v->MENU_LINK)."'>".$v->MENU_NAME."</a>";
			$d_menus .= $this->setsubmenu($this->M_profiling, $prof_id, $v->MENU_ID);
			$d_menus .= "</li>";
		}
		$d_menus .= "</ul>";
		$i=0; $fixlen = 600;
		while(strlen($d_menus) >0) {
			$this->session->set_userdata('d_menus_'.$i, substr($d_menus,0,$fixlen) );
			//echo 'd_menus_'.$i.":\n".substr($d_menus,0,$fixlen)."\n\n";
			//echo "<pre>"; echo $this->session->userdata('d_menus_'.$i); echo "</pre>\n\n"; 
			
			$d_menus = substr($d_menus,$fixlen,strlen($d_menus)-$fixlen);
			$i++;
		}
		
		//$this->session->set_userdata('d_menus', $d_menus);
		//$this->session->set_userdata('d_sub_menus', $this->d_sub_menus);
	}
	
	private function setsubmenu($objprof, $prof_id, $parent) {
		$d_sub_menu = "";
		$sub = $objprof->getMenuByProf($prof_id, $parent);
		if(count($sub) > 0) {
			$d_sub_menu .= "<ul>";
			foreach($sub as $k => $v) {
				$d_sub_menu .= "<li><a href='".site_url($v->MENU_LINK)."'>".$v->MENU_NAME."</a>";
				$d_sub_menu .= $this->setsubmenu($objprof, $prof_id,$v->MENU_ID);
				$d_sub_menu .= "</li>";
			}
			$d_sub_menu .= "</ul>";
		}
		return $d_sub_menu;
	}
	
	public function logout() {
		$this->session->sess_destroy();
		redirect("/home");
	}
}
