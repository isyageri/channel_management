<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class rpt extends CI_Controller {
	
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
		$this->load->model('M_tenant');
		$this->load->model('M_npk');
		$this->load->model('M_c2bi');
		$this->load->model('M_report');
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

	private function format($v) {
		
		return number_format($v,0,",",".");
	}
	
	public function c2birinta() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_rpt_c2bi_rinta";
		$pm["mid-content"]["dt"] = array();
		$pm["mid-content"]["ten"] = array();
		$pm["mid-content"]["pgl"] = array();
		$this->load->model('M_user');
		if($this->M_user->c2bi_prof==$this->session->userdata('d_prof_id')) {
			$pgls = $this->M_user->getPgl($this->session->userdata('d_user_id'));
		} else {
			$pgls = $this->M_pengelola->getLists();
		}
		foreach($pgls as $k => $v) {
			$pm["mid-content"]["pgl"][$v->PGL_ID] = $v->PGL_NAME;
		}
		$this->filtering();
		if(count($this->filters)>1 
		&& isset($this->filters["f_pgl_id"]) 
		&& isset($this->filters["ten_id"]) 
		&& isset($this->filters["f_period_y"])
		&& isset($this->filters["f_period_m"]) ) {
			if($this->filters["ten_id"]!="") {
				foreach($this->M_tenant->getFromPgl($this->filters["f_pgl_id"]) as $k => $v) {
					$pm["mid-content"]["ten"][$v->TEN_ID] = $v->TEN_NAME;
				}
				$pm["mid-content"]["dt"] = $this->M_c2bi->getRinta($this->filters["f_period_y"].$this->filters["f_period_m"], $this->filters["f_pgl_id"], $this->filters["ten_id"]);
			}
		}
	
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}

	public function c2birintano() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_rpt_c2bi_rintano";
		$pm["mid-content"]["dt"] = array();
		$this->filtering();
		if(count($this->filters)>1 
		&& isset($this->filters["f_nd"])
		&& isset($this->filters["f_period_y"]) 
		&& isset($this->filters["f_period_m"]) ) {
			if($this->filters["f_period_y"]!="" && $this->filters["f_period_m"]!="" && $this->filters["f_nd"]!="")
				$pm["mid-content"]["dt"] = $this->M_c2bi->getRintaPerNo($this->filters["f_period_y"].$this->filters["f_period_m"], $this->filters["f_nd"]);
		}
	
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function c2birintajus() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_rpt_c2bi_rintajus";
		$pm["mid-content"]["dt"] = array();
		$pm["mid-content"]["dti"] = array();
		$this->filtering();
		if(count($this->filters)>1 
		&& isset($this->filters["f_nd"])
		&& isset($this->filters["f_period_y"]) 
		&& isset($this->filters["f_period_m"]) ) {
			if($this->filters["f_period_y"]!="" && $this->filters["f_period_m"]!="" && $this->filters["f_nd"]!="") { 
				$pm["mid-content"]["dt"] = $this->M_c2bi->getRintaAjus($this->filters["f_period_y"].$this->filters["f_period_m"], $this->filters["f_nd"]);
		} 
		if  ($this->filters["f_period_y"]!="" && $this->filters["f_period_m"]!="" && $this->filters["f_nd"]!="") {
			$pm["mid-content"]["dti"] = $this->M_c2bi->getRintaDet($this->filters["f_period_y"].$this->filters["f_period_m"], $this->filters["f_nd"]);
		}
		
		} 
	
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function trendfee() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_rpt_trendfee";
		$pm["mid-content"]["dt"] = array();
		$pm["mid-content"]["rev"] = array();
		$pm["mid-content"]["pgl"] = array();
		foreach($this->M_pengelola->getLists() as $k => $v) {
			$pm["mid-content"]["pgl"][$v->PGL_ID] = $v->PGL_NAME;
		}
		$this->filtering();
		if(count($this->filters)>1 
		&& isset($this->filters["f_pgl_id"])
		&& isset($this->filters["f_period_y"]) ) {
			if($this->filters["f_period_y"]!="" && $this->filters["f_pgl_id"]!="") {
				$pm["mid-content"]["dt"] = $this->M_report->trendFeePerPgl($this->filters["f_pgl_id"], $this->filters["f_period_y"]);
				$pm["mid-content"]["rev"] = $this->M_report->trendRevPerPgl($this->filters["f_pgl_id"], $this->filters["f_period_y"]);
			} elseif($_POST["f_period_y"]!="") {
				$pm["mid-content"]["dt"] = $this->M_report->trendFeeTotal($this->filters["f_period_y"]);
				$pm["mid-content"]["rev"] = $this->M_report->trendRevTotal($this->filters["f_period_y"]);
			} else {
				$pm["mid-content"]["dt"] = $this->M_report->trendFeeTotal(date("Y"));
				$pm["mid-content"]["rev"] = $this->M_report->trendRevTotal(date("Y"));
			}
		} 
	
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}

		// Sheet Output
	public function rintasheet($pgl_id, $ten_id, $period) {
		// Sheet
		$this->load->library("phpexcel");
		$filename = "rinta_".$pgl_id.$ten_id."_".$period.".xls";
		$this->phpexcel->getProperties()->setCreator("PT Telekomunikasi Indonesia, Tbk")
			 ->setLastModifiedBy("PT Telekomunikasi Indonesia, Tbk")
			 ->setTitle("REPORT")
			 ->setKeywords("office 2007 openxml php")
			 ->setCategory("Rincian Tagihan");
		$this->phpexcel->setActiveSheetIndex(0);
		$sh = & $this->phpexcel->getActiveSheet();
		$sh->setCellValue('A1', 'ND')
		   ->setCellValue('B1', 'ABONEMEN')
		   ->setCellValue('C1', 'KREDIT')
       ->setCellValue('D1', 'DEBET')
		   ->setCellValue('E1', 'LOKAL')
		   ->setCellValue('F1', 'INTERLOKAL')
		   ->setCellValue('G1', 'SLJJ')
		   ->setCellValue('H1', 'SLI007')
		   ->setCellValue('I1', 'SLI001')
		   ->setCellValue('J1', 'SLI008')
		   ->setCellValue('K1', 'SLI009')
		   ->setCellValue('L1', 'TELKOM GLOBAL 017')
		   ->setCellValue('M1', 'TELKOMNET INSTAN')
		   ->setCellValue('N1', 'TELKOMSAVE')
		   ->setCellValue('O1', 'STB')   
		   ->setCellValue('P1', 'JAPATI')
		   ->setCellValue('Q1', 'SPEEDY USAGE')
		   ->setCellValue('R1', 'NON JASTEL')   
		   ->setCellValue('S1', 'ISDN DATA')
		   ->setCellValue('T1', 'ISDN VOICE')
		   ->setCellValue('U1', 'KONTEN')   
		   ->setCellValue('V1', 'PORTWHOLESALES')
		   ->setCellValue('W1', 'METERAI')
		   ->setCellValue('X1', 'PPN')
		   ->setCellValue('Y1', 'TOTAL RINCIAN')
		   ->setCellValue('Z1', 'GRAND TOTAL')
		   ->setCellValue('AA1', 'STATUS BAYAR')
		   ->setCellValue('AB1', 'TGL BAYAR')
		   ->setCellValue('AC1', 'NAMA PLG')    
		;

		$sh->getStyle('A1:AA1')->getFont()->setBold(TRUE);
		$sh->getColumnDimension('A')->setAutoSize(TRUE);
		$sh->getColumnDimension('B')->setAutoSize(TRUE);
		$sh->getColumnDimension('C')->setAutoSize(TRUE);
		$sh->getColumnDimension('D')->setAutoSize(TRUE);
		$sh->getColumnDimension('E')->setAutoSize(TRUE);
		$sh->getColumnDimension('F')->setAutoSize(TRUE);
		$sh->getColumnDimension('G')->setAutoSize(TRUE);
		$sh->getColumnDimension('H')->setAutoSize(TRUE);
		$sh->getColumnDimension('I')->setAutoSize(TRUE);
		$sh->getColumnDimension('J')->setAutoSize(TRUE);
		$sh->getColumnDimension('K')->setAutoSize(TRUE);
		$sh->getColumnDimension('L')->setAutoSize(TRUE);
		$sh->getColumnDimension('M')->setAutoSize(TRUE);
		$sh->getColumnDimension('N')->setAutoSize(TRUE);
		$sh->getColumnDimension('O')->setAutoSize(TRUE);
		$sh->getColumnDimension('P')->setAutoSize(TRUE);
		$sh->getColumnDimension('Q')->setAutoSize(TRUE);
		$sh->getColumnDimension('R')->setAutoSize(TRUE);
		$sh->getColumnDimension('S')->setAutoSize(TRUE);
		$sh->getColumnDimension('T')->setAutoSize(TRUE);
		$sh->getColumnDimension('U')->setAutoSize(TRUE);
		$sh->getColumnDimension('V')->setAutoSize(TRUE);
		$sh->getColumnDimension('W')->setAutoSize(TRUE);
		$sh->getColumnDimension('X')->setAutoSize(TRUE);
		$sh->getColumnDimension('Y')->setAutoSize(TRUE);
		$sh->getColumnDimension('Z')->setAutoSize(TRUE);
		$sh->getColumnDimension('AA')->setAutoSize(TRUE);
		$sh->getColumnDimension('AB')->setAutoSize(TRUE);
		$sh->getColumnDimension('AC')->setAutoSize(TRUE);
		$sh->getStyle('A1:AC1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('A1:AC1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
		$sh->getStyle('A1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('B1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('C1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('D1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('E1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('F1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('G1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('H1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('I1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('J1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('K1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('L1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('M1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('N1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('O1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('P1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('Q1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('R1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('S1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('T1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('U1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('V1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('W1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('X1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('Y1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('Z1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('AA1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('AB1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('AC1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('AC1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		
		$x = 2;
		$dt = $this->M_c2bi->getRinta($period, $pgl_id, $ten_id);
		$no = 1;
		foreach($dt as $k => $r) {
			$sh->getCell('A'.$x)->setValueExplicit($r->ND1, PHPExcel_Cell_DataType::TYPE_STRING);
			$sh->setCellValue('B'.$x, @$r->ABONEMEN);
			$sh->setCellValue('C'.$x, @$r->MNT_TCK_C);
			$sh->setCellValue('D'.$x, @$r->MNT_TCK_D);
			$sh->setCellValue('E'.$x, @$r->LOKAL);
			$sh->setCellValue('F'.$x, @$r->INTERLOKAL);
			$sh->setCellValue('G'.$x, @$r->SLJJ);
			$sh->setCellValue('H'.$x, @$r->SLI007);
			$sh->setCellValue('I'.$x, @$r->SLI001);
			$sh->setCellValue('J'.$x, @$r->SLI008);
			$sh->setCellValue('K'.$x, @$r->SLI009);
			$sh->setCellValue('L'.$x, @$r->SLI_017);
			$sh->setCellValue('M'.$x, @$r->TELKOMNET_INSTAN);
			$sh->setCellValue('N'.$x, @$r->TELKOMSAVE);
			$sh->setCellValue('O'.$x, @$r->STB);
			$sh->setCellValue('P'.$x, @$r->JAPATI);
			$sh->setCellValue('Q'.$x, @$r->USAGE_SPEEDY);
			$sh->setCellValue('R'.$x, @$r->NON_JASTEL);
			$sh->setCellValue('S'.$x, @$r->ISDN_DATA);
			$sh->setCellValue('T'.$x, @$r->ISDN_VOICE);
			$sh->setCellValue('U'.$x, @$r->KONTEN);
			$sh->setCellValue('V'.$x, @$r->PORTWHOLESALES);
			$sh->setCellValue('W'.$x, @$r->METERAI);
			$sh->setCellValue('X'.$x, @$r->PPN);
			$sh->setCellValue('Y'.$x, @$r->TOTAL);
			$sh->setCellValue('Z'.$x, @$r->GRAND_TOTAL);
			$sh->getCell('AA'.$x)->setValueExplicit($r->STATUS_PEMBAYARAN, PHPExcel_Cell_DataType::TYPE_STRING);
			$sh->getCell('AB'.$x)->setValueExplicit($r->TGL_BYR, PHPExcel_Cell_DataType::TYPE_STRING);
			$sh->setCellValue('AC'.$x, @$r->NOM);
			$no++;
			$x++;
			//if($x==8000) break;
		}
		$sh->getStyle('A2:A'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('B2:B'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('C2:C'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('D2:D'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('E2:E'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('F2:F'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('G2:G'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('H2:H'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('I2:I'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('J2:J'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('K2:K'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('L2:L'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('M2:M'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('N2:N'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('O2:O'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('P2:P'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('Q2:Q'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('R2:R'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('S2:S'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('T2:T'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('U2:U'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('V2:V'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('W2:W'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('X2:X'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('Y2:Y'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('Z2:Z'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('AA2:AA'.$x)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('AA2:AA'.$x)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('B2'.':AC'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
		$sh->getStyle('A'.$x.':AC'.$x)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('A'.$x.':AC'.$x)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		
		$sh->setCellValue('A'.$x, 'TOTAL');
		$sh->setCellValue('B'.$x, "=SUM(B2:B".($x-1).")");
		$sh->getStyle('B'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
		$sh->setCellValue('C'.$x, "=SUM(C2:C".($x-1).")");
		$sh->getStyle('C'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
		$sh->setCellValue('D'.$x, "=SUM(D2:D".($x-1).")");
		$sh->getStyle('D'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
		$sh->setCellValue('E'.$x, "=SUM(E2:E".($x-1).")");
		$sh->getStyle('E'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
		$sh->setCellValue('F'.$x, "=SUM(F2:F".($x-1).")");
		$sh->getStyle('F'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
		$sh->setCellValue('G'.$x, "=SUM(G2:G".($x-1).")");
		$sh->getStyle('G'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
		$sh->setCellValue('H'.$x, "=SUM(H2:H".($x-1).")");
		$sh->getStyle('H'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
		$sh->setCellValue('I'.$x, "=SUM(I2:I".($x-1).")");
		$sh->getStyle('I'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
		$sh->setCellValue('J'.$x, "=SUM(J2:J".($x-1).")");
		$sh->getStyle('J'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
		$sh->setCellValue('K'.$x, "=SUM(K2:K".($x-1).")");
		$sh->getStyle('K'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
		$sh->setCellValue('L'.$x, "=SUM(L2:L".($x-1).")");
		$sh->getStyle('L'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
		$sh->setCellValue('M'.$x, "=SUM(M2:M".($x-1).")");
		$sh->getStyle('M'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
		$sh->setCellValue('N'.$x, "=SUM(N2:N".($x-1).")");
		$sh->getStyle('N'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
		$sh->setCellValue('O'.$x, "=SUM(O2:O".($x-1).")");
		$sh->getStyle('O'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
		$sh->setCellValue('P'.$x, "=SUM(P2:P".($x-1).")");
		$sh->getStyle('P'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
		$sh->setCellValue('Q'.$x, "=SUM(Q2:Q".($x-1).")");
		$sh->getStyle('Q'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
		$sh->setCellValue('R'.$x, "=SUM(R2:R".($x-1).")");
		$sh->getStyle('R'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
		$sh->setCellValue('S'.$x, "=SUM(S2:S".($x-1).")");
		$sh->getStyle('S'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
		$sh->setCellValue('T'.$x, "=SUM(T2:T".($x-1).")");
		$sh->getStyle('T'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);

		$sh->setCellValue('U'.$x, "=SUM(U2:U".($x-1).")");
    $sh->getStyle('U'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
		$sh->setCellValue('V'.$x, "=SUM(V2:V".($x-1).")");

		$sh->getStyle('V'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
		
		$sh->setCellValue('W'.$x, "=SUM(W2:W".($x-1).")");
    $sh->getStyle('W'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
    
    $sh->setCellValue('X'.$x, "=SUM(X2:X".($x-1).")");
    $sh->getStyle('X'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
    
    $sh->setCellValue('Y'.$x, "=SUM(Y2:Y".($x-1).")");
    $sh->getStyle('Y'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
    
      $sh->setCellValue('Z'.$x, "=SUM(Z2:Z".($x-1).")");
    $sh->getStyle('Z'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
    
    $sh->setCellValue('AA'.$x, "");
    $sh->getStyle('AA'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
    
    $sh->setCellValue('AB'.$x, "");
    $sh->getStyle('AB'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
    
    $sh->setCellValue('AC'.$x, "");
    $sh->getStyle('AC'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
    
    $sh->setCellValue('A'.$x, '');
		
		
		$sh->getStyle('A'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('B'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('C'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('D'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('E'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('F'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('G'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('H'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('I'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('J'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('K'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('L'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('M'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('N'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('O'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('P'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('Q'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('R'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('S'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('T'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('U'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('V'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('W'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('X'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('Y'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('Z'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('AA'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('AB'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('AC'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('AC'.$x)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('A'.$x.':AC'.$x)->getFont()->setBold(TRUE);
		
		$objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
		$objWriter->save(dirname(__FILE__).'/../third_party/report/'.$filename);
		redirect($this->config->config['base_url'].'application/third_party/report/'.$filename, 'location', 301);
	}

	public function rintanosheet($nd, $period) {
		// Sheet
		$this->load->library("phpexcel");
		$filename = "rintano_".$nd."_".$period.".xls";
		$this->phpexcel->getProperties()->setCreator("PT Telekomunikasi Indonesia, Tbk")
			 ->setLastModifiedBy("PT Telekomunikasi Indonesia, Tbk")
			 ->setTitle("REPORT")
			 ->setKeywords("office 2007 openxml php")
			 ->setCategory("Rincian Tagihan");
		$this->phpexcel->setActiveSheetIndex(0);
		$sh = & $this->phpexcel->getActiveSheet();
		$sh->setCellValue('A1', 'No.')
		   ->setCellValue('B1', 'Pemanggil')
		   ->setCellValue('C1', 'No. Dipanggil')
		   ->setCellValue('D1', 'Tujuan')
		   ->setCellValue('E1', 'Tanggal / Jam')
		   ->setCellValue('F1', 'Durasi (detik)')
		   ->setCellValue('G1', 'Biaya')
		;
		$sh->getStyle('A1:G1')->getFont()->setBold(TRUE);
		$sh->getColumnDimension('A')->setAutoSize(TRUE);
		$sh->getColumnDimension('B')->setAutoSize(TRUE);
		$sh->getColumnDimension('C')->setAutoSize(TRUE);
		$sh->getColumnDimension('D')->setAutoSize(TRUE);
		$sh->getColumnDimension('E')->setAutoSize(TRUE);
		$sh->getColumnDimension('F')->setAutoSize(TRUE);
		$sh->getColumnDimension('G')->setAutoSize(TRUE);
		$sh->getStyle('A1:G1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('A1:G1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
		$sh->getStyle('A1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('B1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('C1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('D1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('E1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('F1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('G1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('G1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		
		$x = 2;
		$dt = $this->M_c2bi->getRintaPerNo($period, $nd);
		$no = 1;
		foreach($dt as $k => $r) {
			$sh->setCellValue('A'.$x, $no);   
			$sh->getCell('B'.$x)->setValueExplicit($r->ND, PHPExcel_Cell_DataType::TYPE_STRING);
			$sh->getCell('C'.$x)->setValueExplicit($r->ND_APPELE, PHPExcel_Cell_DataType::TYPE_STRING);
			$sh->setCellValue('D'.$x, $r->TUJUAN);
			$sh->setCellValue('E'.$x, $r->TGL_JAM);
			$sh->setCellValue('F'.$x, $r->DURASI);
			$sh->getStyle('F'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
			$sh->setCellValue('G'.$x, $r->BIAYA);
			$sh->getStyle('G'.$x)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED0);
			
			$sh->getStyle('A'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$sh->getStyle('B'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$sh->getStyle('C'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$sh->getStyle('D'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$sh->getStyle('E'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$sh->getStyle('F'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$sh->getStyle('G'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$sh->getStyle('G'.$x)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$no++;
			$x++;
		}
		$sh->getStyle('A'.$x.':G'.$x)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		
		$objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
		$objWriter->save(dirname(__FILE__).'/../third_party/report/'.$filename);
		redirect($this->config->config['base_url'].'application/third_party/report/'.$filename, 'location', 301);
	}
	
	
	// History
	public function ndchurn() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_rpt_ndchurn";
		$pm["mid-content"]["dt"] = array();
		$pm["mid-content"]["ten"] = array();
		$pm["mid-content"]["pgl"] = array();
		foreach($this->M_pengelola->getLists() as $k => $v) {
			$pm["mid-content"]["pgl"][$v->PGL_ID] = $v->PGL_NAME;
		}
		$this->filtering();
		if(count($this->filters)>1 
		&& isset($this->filters["f_pgl_id"]) 
		&& isset($this->filters["ten_id"]) 
		&& isset($this->filters["f_period_y"])
		&& isset($this->filters["f_period_m"]) ) {
			if($this->filters["ten_id"]!="") {
				foreach($this->M_tenant->getFromPgl($this->filters["f_pgl_id"]) as $k => $v) {
					$pm["mid-content"]["ten"][$v->TEN_ID] = $v->TEN_NAME;
				}
				if($this->filters["f_period_y"]!="" && $this->filters["f_period_m"]!="") 
					$pm["mid-content"]["dt"] = $this->M_report->getNDChurnHist($this->filters["ten_id"], $this->filters["f_period_y"].$this->filters["f_period_m"]);
			}
		}
		
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}
	
	public function ndchurnsheet($pgl_id, $ten_id, $period) {
		// Sheet
		$this->load->library("phpexcel");
		$filename = "ndchurn_".$ten_id."_".$period.".xls";
		$this->phpexcel->getProperties()->setCreator("PT Telekomunikasi Indonesia, Tbk")
			 ->setLastModifiedBy("PT Telekomunikasi Indonesia, Tbk")
			 ->setTitle("REPORT")
			 ->setKeywords("office 2007 openxml php")
			 ->setCategory("ND Churn");
		$this->phpexcel->setActiveSheetIndex(0);
		$sh = & $this->phpexcel->getActiveSheet();
		$sh->setCellValue('A1', 'No.')
		   ->setCellValue('B1', 'ND')
		;
		$sh->getStyle('A1:B1')->getFont()->setBold(TRUE);
		$sh->getColumnDimension('A')->setAutoSize(TRUE);
		$sh->getColumnDimension('B')->setAutoSize(TRUE);
		$sh->getStyle('A1:B1')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('A1:B1')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_MEDIUM);
		$sh->getStyle('A1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('B1')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$sh->getStyle('B1')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		
		$x = 2;
		$dt = $this->M_report->getNDChurnHist($ten_id, $period);
		$no = 1;
		foreach($dt as $k => $r) {
			$sh->setCellValue('A'.$x, $no);   
			$sh->getCell('B'.$x)->setValueExplicit($r->ND, PHPExcel_Cell_DataType::TYPE_STRING);
			
			$sh->getStyle('A'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$sh->getStyle('B'.$x)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$sh->getStyle('B'.$x)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$no++;
			$x++;
		}
		$sh->getStyle('A'.$x.':B'.$x)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		
		$objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
		$objWriter->save(dirname(__FILE__).'/../third_party/report/'.$filename);
		redirect($this->config->config['base_url'].'application/third_party/report/'.$filename, 'location', 301);
	}
	
}


?>