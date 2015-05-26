<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ten extends CI_Controller {
	
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
		$this->load->model('M_tenant');
                $this->load->model('M_param');
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
		$ct["mid-content"] = "v_ten";
                $cond = "";
		$this->filtering();
		if(count($this->filters)>1 
		&& isset($this->filters["f_ten_name"]) ) {
			if($this->filters["f_ten_name"]!="") $cond .= "LOWER(TEN_NAME) LIKE '%".strtolower($this->filters["f_ten_name"])."%'";
		}
		$pm["mid-content"]["dt"] = $this->M_tenant->getLists($cond);
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function tenadd() {
		$this->load->model('M_pengelola');
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_ten_add";
		$pm["mid-content"]["pgl"] = array();
		foreach($this->M_pengelola->getLists() as $k => $v) {
			$pm["mid-content"]["pgl"][$v->PGL_ID] = $v->PGL_NAME;
		}
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function tenadddo() {
		if(isset($_POST["submit"])) {
			$ten = $this->M_tenant->getLists("UPPER(TEN_NAME)='".strtoupper($_POST['ten_name'])."'");
			if( !(count($ten) > 0) ) 
				$ten_id = $this->M_tenant->insert(0, 0, $_POST['ten_name'], $_POST['ten_addr'], $_POST['ten_contact_no']);
			if(isset($_POST["pgl_id"])) {
				foreach($_POST["pgl_id"] as $k => $v) {
					$this->M_tenant->setPengelola($ten_id, $v);
				}
			}
		}
		redirect("/ten");
	}
	
	public function tenedit($ten_id) {
		$this->load->model('M_pengelola');
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_ten_edit";
		$pm["mid-content"]["dt"] = $this->M_tenant->getLists("TEN_ID=".$ten_id);
		$pm["mid-content"]["pgl"] = array();
		$pm["mid-content"]["pgl_select"] = $this->M_tenant->getPengelola($ten_id);
		foreach($this->M_pengelola->getLists() as $k => $v) {
			$pm["mid-content"]["pgl"][$v->PGL_ID] = $v->PGL_NAME;
		}
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function teneditdo() {
		if(isset($_POST["submit"])) {
			$this->M_tenant->update($_POST['ten_id'], $_POST['ncli'], $_POST['ndos'], $_POST['ten_name'], $_POST['ten_addr'], $_POST['ten_contact_no']);
			if(isset($_POST["pgl_id"])) {
				$this->M_tenant->clearPengelola($_POST['ten_id']);
				foreach($_POST["pgl_id"] as $k => $v) {
					$this->M_tenant->setPengelola($_POST['ten_id'], $v);
				}
			}
		}
		redirect("/ten");
	}
	
	public function tendel($ten_id) {
		$this->M_tenant->remove($ten_id);
		$this->M_tenant->clearPengelola($ten_id);
		redirect("/ten");
	}
	
	public function tenupload() {
		$this->load->model('M_pengelola');
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_data_ten";
		$pm["mid-content"]["pgl"] = array();
		foreach($this->M_pengelola->getLists() as $k => $v) {
			$pm["mid-content"]["pgl"][$v->PGL_ID] = $v->PGL_NAME;
		}
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	// ND
	public function nd($ten_id) {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_ten_nd";
		$pm["mid-content"]["dt"] = $this->M_tenant->getND($ten_id);
		$pm["mid-content"]["ten"] = $this->M_tenant->getLists("TEN_ID=".$ten_id);
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function ndsheet($ten_id) {
		// Sheet
		$this->load->library("phpexcel");
		$filename = "nd_".$ten_id.".xls";
		$this->phpexcel->getProperties()->setCreator("PT Telekomunikasi Indonesia, Tbk")
			 ->setLastModifiedBy("PT Telekomunikasi Indonesia, Tbk")
			 ->setTitle("NPK")
			 ->setKeywords("office 2007 openxml php")
			 ->setCategory("Marketing Fee");
		$this->phpexcel->setActiveSheetIndex(0);
		$sh = & $this->phpexcel->getActiveSheet();
		
		$i=1; $sh->getColumnDimension('A')->setWidth(20);
		foreach($this->M_tenant->getND($ten_id) as $k => $v) {
			$sh->getCell('A'.$i)->setValueExplicit($v->ND, PHPExcel_Cell_DataType::TYPE_STRING);
			$sh->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$i++;
		}

		$objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
		$objWriter->save(dirname(__FILE__).'/../third_party/report/'.$filename);
		redirect($this->config->config['base_url'].'application/third_party/report/'.$filename, 'location', 301);
	}
	
	public function ndupload() {
		$this->load->model('M_pengelola');
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_data_nd";
		$pm["mid-content"]["pgl"] = array();
		foreach($this->M_pengelola->getLists() as $k => $v) {
			$pm["mid-content"]["pgl"][$v->PGL_ID] = $v->PGL_NAME;
		}
                $pm["mid-content"]["cprod"] = $this->M_param->getParamProducts();
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function nduploaddo() {
		if(isset($_POST["submit"])) {
			if($_POST["ten_id"]!="") {
				switch($_POST["pu_action"]) {
					case 1: 
						$this->M_tenant->NDBackupToCurrPeriod($_POST["ten_id"]);
						break;
					case 2: 
						$this->M_tenant->NDBackupToPrevPeriod($_POST["ten_id"]);
						break;
				}
				
				// Upload Process 
				$config['upload_path'] = './application/third_party/upload';
				$config['allowed_types'] = 'xls|xlsx|csv';
				$config['max_size']	= '10000000';
				$config['overwrite']  = TRUE;
				$file_id = date("YmdHis");
				$config['file_name']  = "nd_".$file_id;

				$this->load->library('upload');
				$this->upload->initialize($config);
					
				if ( ! $this->upload->do_upload("file_name")) {
					$error = $this->upload->display_errors(); 
					echo "<script>";
					echo "alert('Error: ".$error."');";
					echo "document.location.href='".site_url("/ten/ndupload")."';";
					echo "</script>";
				} else {
					$data = $this->upload->data(); 
					echo "<script>";
					echo "document.location.href='".site_url("/ten/nduploadparse/".$data["file_name"]."/".$data["file_ext"]."/".$_POST["cprod"]."/".$_POST["ten_id"])."';";
					echo "</script>";
				}
			} else {
				echo "<script>";
				echo "alert('Select Tenant first.');";
				echo "document.location.href='".site_url("/ten/ndupload")."';";
				echo "</script>";
			}
		} else {
			redirect("/ten/ndupload");
		}

	}
	
	public function nduploadparse($file_name, $file_ext, $cprod, $ten_id="") {
		$this->load->library('phpexcel');
		
		if($file_ext==".xlsx") $readerType = 'Excel2007';
		elseif($file_ext==".xls") $readerType = 'Excel5';
		elseif($file_ext==".csv"||$file_ext==".txt") $readerType = 'CSV';
		
		$reader = PHPExcel_IOFactory::createReader($readerType);
		$reader->setReadDataOnly(true);
		$phpexcel = $reader->load(APPPATH.'third_party/upload/'.$file_name);
		$sh = $phpexcel->getActiveSheet();
		$highestRow = $sh->getHighestRow(); 
		$highestColumn = $sh->getHighestColumn(); 
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
		
		if($ten_id!="") {
			if($highestRow>0) $this->M_tenant->clearNDTen($ten_id);
			for ($row = 1; $row <= $highestRow; ++$row) {
				$nd = $sh->getCellByColumnAndRow(0, $row)->getValue();
				if(trim($nd)!="") {
					$this->M_tenant->insertTenND($ten_id, str_replace("'", "", $nd), $cprod );
				}
			}
		}
		
		echo "<script>";
		echo "alert('All records has been inserted successfully !');";
		echo "document.location.href='".site_url("/ten/ndupload")."';";
		echo "</script>";
		
	}
        
        public function nddel($ten_id, $nd) {
		$this->M_tenant->delNDTen($ten_id, $nd);
		redirect("/ten/nd/".$ten_id);
	}
        
        // ND Non POTS
	public function ndnp($ten_id) {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_ten_ndnp";
		$pm["mid-content"]["dt"] = $this->M_tenant->getNDNP($ten_id);
		$pm["mid-content"]["ten"] = $this->M_tenant->getLists("TEN_ID=".$ten_id);
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function ndnpsheet($ten_id) {
		// Sheet
		$this->load->library("phpexcel");
		$filename = "ndnp_".$ten_id.".xls";
		$this->phpexcel->getProperties()->setCreator("PT Telekomunikasi Indonesia, Tbk")
			 ->setLastModifiedBy("PT Telekomunikasi Indonesia, Tbk")
			 ->setTitle("NPK")
			 ->setKeywords("office 2007 openxml php")
			 ->setCategory("Marketing Fee");
		$this->phpexcel->setActiveSheetIndex(0);
		$sh = & $this->phpexcel->getActiveSheet();
		
		$i=1; $sh->getColumnDimension('A')->setWidth(20);
		foreach($this->M_tenant->getNDNP($ten_id) as $k => $v) {
			$sh->getCell('A'.$i)->setValueExplicit($v->CUSTOMER_REF, PHPExcel_Cell_DataType::TYPE_STRING);
			$sh->getStyle('A'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $sh->getCell('B'.$i)->setValueExplicit($v->ACCOUNT_NUM, PHPExcel_Cell_DataType::TYPE_STRING);
			$sh->getStyle('B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                        $sh->getCell('C'.$i)->setValueExplicit($v->GL_ACCOUNT, PHPExcel_Cell_DataType::TYPE_STRING);
			$sh->getStyle('C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$i++;
		}

		$objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
		$objWriter->save(dirname(__FILE__).'/../third_party/report/'.$filename);
		redirect($this->config->config['base_url'].'application/third_party/report/'.$filename, 'location', 301);
	}
        
        public function ndnpdel($ten_id, $acc_no) {
		$this->M_tenant->delNDNPTen($ten_id, $acc_no);
		redirect("/ten/ndnp/".$ten_id);
	}
        
        public function ndnpupload() {
		$this->load->model('M_pengelola');
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_data_ndnp";
		$pm["mid-content"]["pgl"] = array();
		foreach($this->M_pengelola->getLists() as $k => $v) {
			$pm["mid-content"]["pgl"][$v->PGL_ID] = $v->PGL_NAME;
		}
                $pm["mid-content"]["cprod"] = $this->M_param->getParamProducts();
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function ndnpuploaddo() {
		if(isset($_POST["submit"])) {
			if($_POST["ten_id"]!="") {
				
				// Upload Process 
				$config['upload_path'] = './application/third_party/upload';
				$config['allowed_types'] = 'xls|xlsx|csv';
				$config['max_size']	= '10000000';
				$config['overwrite']  = TRUE;
				$file_id = date("YmdHis");
				$config['file_name']  = "ndnp_".$file_id;

				$this->load->library('upload');
				$this->upload->initialize($config);
					
				if ( ! $this->upload->do_upload("file_name")) {
					$error = $this->upload->display_errors(); 
					echo "<script>";
					echo "alert('Error: ".$error."');";
					echo "document.location.href='".site_url("/ten/ndnpupload")."';";
					echo "</script>";
				} else {
					$data = $this->upload->data(); 
					echo "<script>";
					echo "document.location.href='".site_url("/ten/ndnpuploadparse/".$data["file_name"]."/".$data["file_ext"]."/".$_POST["ten_id"])."';";
					echo "</script>";
				}
			} else {
				echo "<script>";
				echo "alert('Select Tenant first.');";
				echo "document.location.href='".site_url("/ten/ndnpupload")."';";
				echo "</script>";
			}
		} else {
			redirect("/ten/ndnpupload");
		}

	}
	
	public function ndnpuploadparse($file_name, $file_ext, $ten_id="") {
		$this->load->library('phpexcel');
		
		if($file_ext==".xlsx") $readerType = 'Excel2007';
		elseif($file_ext==".xls") $readerType = 'Excel5';
		elseif($file_ext==".csv"||$file_ext==".txt") $readerType = 'CSV';
		
		$reader = PHPExcel_IOFactory::createReader($readerType);
		$reader->setReadDataOnly(true);
		$phpexcel = $reader->load(APPPATH.'third_party/upload/'.$file_name);
		$sh = $phpexcel->getActiveSheet();
		$highestRow = $sh->getHighestRow(); 
		$highestColumn = $sh->getHighestColumn(); 
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
		
		if($ten_id!="") {
			if($highestRow>0) $this->M_tenant->clearNDNPTen($ten_id);
			for ($row = 1; $row <= $highestRow; ++$row) {
				$cust_reff = $sh->getCellByColumnAndRow(0, $row)->getValue();
                                $acc_no    = $sh->getCellByColumnAndRow(1, $row)->getValue();
                                $gl_acc    = $sh->getCellByColumnAndRow(2, $row)->getValue();
				if(trim($acc_no)!="") {
					$this->M_tenant->insertTenNDNP($ten_id, $cust_reff, $acc_no, $gl_acc );
				}
			}
		}
		
		echo "<script>";
		echo "alert('All records has been inserted successfully !');";
		echo "document.location.href='".site_url("/ten/ndnpupload")."';";
		echo "</script>";
		
	}
	
	
	// Restore ND
	public function ndrest() {
		$this->load->model('M_pengelola');
		$this->load->model('M_npk');
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_data_nd_rest";
		$pm["mid-content"]["pgl"] = array();
		foreach($this->M_pengelola->getLists() as $k => $v) {
			$pm["mid-content"]["pgl"][$v->PGL_ID] = $v->PGL_NAME;
		}
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function ndrestdo() {
		if(isset($_POST["submit"])) {
			if( $_POST["period_y"]!="" && $_POST["period_m"]!="" && $_POST["ten_id"]!="" ) {
				$this->M_tenant->NDRestore($_POST['period_y'].$_POST['period_m'], $_POST['ten_id']);
				redirect("/ten/nd/".$_POST["ten_id"]);
			}
		}
		redirect("/ten/ndrest");
	}
        
        public function nonpots() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_nonpots";
		$pm["mid-content"]["dt"] = $this->M_compfee->getLists("CF_TYPE<>'SDEF'");
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function nonpotsadd() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_nonpots_add";
		$this->load->view('v_body', array("ct"=>$ct) );
	}
	
	public function nonpotsadddo() {
		if(isset($_POST["submit"])) {
			$type = $this->M_compfee->getLists("CF_NAME='".strtoupper($_POST['cf_name'])."'");
			if( !(count($type) > 0)) {
				$this->M_compfee->insert(strtoupper($_POST['cf_name']), $_POST['cf_type'], $_POST['line_type'], strtoupper(trim($_POST['str_formula'])), $_POST['cf_caption']);
			}
		}
		redirect("/comp/nonpots");
	}
	
	public function nonpotsedit($cf_id) {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_nonpots_edit";
		$pm["mid-content"]["dt"] = $this->M_compfee->getLists("CF_ID=".$cf_id);
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function nonpotseditdo() {
		if(isset($_POST["submit"])) {
			$this->M_compfee->update($_POST['cf_id'], strtoupper($_POST['cf_name']), $_POST['cf_type'], $_POST['line_type'], strtoupper(trim($_POST['str_formula'])), $_POST['cf_caption']);
		}
		redirect("/comp/nonpots");
	}
	
	public function nonpotsdel($cf_id) {
		$this->M_compfee->remove($cf_id);
		redirect("/comp/nonpots");
	}

        
}

?>