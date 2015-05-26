<?php

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Your own constructor code
        $this->load->library('session');
      //  $this->load->model('dbfunction');
        $this->load->helper('url');
        //$this->load->helper('form');
        $this->auth = $this->session->userdata('name');
        $this->notif = '';
        session_start();
    }
    public function index() {
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

        redirect("home");
    }

    public function adminlogin() {
        $user = array(
            $this->input->post('username'),
            md5($this->input->post('password'))
        );

        $data['result'] = $this->dbfunction->signIn($user[0], $user[1]);
        if ($data['result'] != null) {
            $this->session->set_userdata('user', $user[0]);
            foreach($data['result'] as $hasil){
                $name = $hasil->AdminName; // ambil nama admin
                $AdminID = $hasil->AdminID;
            }
           // die($name);
            $this->session->set_userdata('name', $name); // isi session dengan username
            $this->session->set_userdata('AdminID', $AdminID); // isi session dengan username


            $IP = $this->input->ip_address(); //get IP ADRESS
            $Activity = "Login";
            $ActivityDesc = "Login into Admin System";
            //die($AdminID);
            $this->dbfunction->adminActivity($AdminID,$IP,$Activity,$ActivityDesc);

            redirect(base_url() . "index.php/home");

        }else {
           $this->notif = "failed";
            $this->index();
//            redirect(base_url() . "home");
        }
    }
    public function logout() {
//        $AdminID =  $this->auth = $this->session->userdata('AdminID');
//        $IP = $this->input->ip_address(); //get IP ADRESS
//        $Activity = "Logout";
//        $ActivityDesc = "Logout from Admin System";
//
//
//        $this->dbfunction->adminActivity($AdminID,$IP,$Activity,$ActivityDesc);

        $this->session->sess_destroy();

        redirect(base_url() . "login");
    }
    public function timeout() {
        $session_id = $this->session->userdata('name');
        if($session_id == '' or $session_id == NULL){
          $status = 'expired';
//            redirect(base_url() . "home");
        }else{
            $status = 'success';
        }
        $data['status'] = $status;
        $this->status = $status;
        $this->load->view('templates/interval',$data);
    }





}
