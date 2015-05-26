<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class doc extends CI_Controller {
	public $month_id;
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
		$this->month_id = array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', 
			'07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember');
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
	
	// NPK
	public function npk() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_doc_npk";
		$cond = "";
		$this->filtering();
		if(count($this->filters)>1 
		&& isset($this->filters["f_pgl_id"]) 
		&& isset($this->filters["f_period_m"]) 
		&& isset($this->filters["f_period_y"]) ) {
			if($this->filters["f_pgl_id"]!="") $cond .= " AND PGL_ID=".$this->filters["f_pgl_id"];
			if($this->filters["f_period_m"]!="" && $this->filters["f_period_y"]!="") 
			$cond .= " AND PERIOD='".$this->filters["f_period_y"].$this->filters["f_period_m"]."'";
		}
		$pm["mid-content"]["dt"] = $this->M_npk->getLists("STATUS IN(3,4,5,6,7,9)".$cond);
		foreach($this->M_pengelola->getLists("ENABLE_FEE='1'") as $k => $v) 
			$pm["mid-content"]["pgl"][$v->PGL_ID] = $v->PGL_NAME;
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}

	public function npktbl($npk_id) {
		$this->load->model("M_compfee");
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_doc_npktbl";
		$pm["mid-content"]["dt"] = $this->M_npk->getLists("NPK_ID=".$npk_id);
		$pm["mid-content"]["pgl"] = $this->M_pengelola->getLists("PGL_ID=".$pm["mid-content"]["dt"][0]->PGL_ID);
		$pm["mid-content"]["proc"] = $this->M_npk->getProcess($npk_id, 0);
		$pm["mid-content"]["proc_exist"] = $this->M_npk->getProcess($npk_id, 1);
		$pm["mid-content"]["tier"] = $this->M_compfee->getTier();
		$pm["mid-content"]["udef"] = $this->M_compfee->getLists("CF_TYPE='UDEF'");
		$pm["mid-content"]["tbl"] = $this->M_npk->getTableFormat($npk_id);
		$pm["mid-content"]["colnum"] = $this->M_npk->getColNum($npk_id);
		$pm["mid-content"]["rownum"] = $this->M_npk->getRowNum($npk_id);
		$pm["mid-content"]["headers"] = $this->M_npk->getColsData($npk_id, 0);
		$pm["mid-content"]["row1"] = $this->M_npk->getRowsData($npk_id, 1);
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}

	public function npktbldo($npk_id) {
		$cols = $_POST['ncol'];
		$rows = $_POST['nrow'];
		if(isset($_POST['comp'])) $rows = $rows + count($_POST['comp']);
		if($_POST['submit']) {
			if( !(count($this->M_npk->getTableFormat($_POST['npk_id'])) > 0)) {
				$this->M_npk->prepareCell($_POST['npk_id'], $cols, $rows);
			} else {
				if($cols != $this->M_npk->getColNum($_POST['npk_id']) || $rows != $this->M_npk->getRowNum($_POST['npk_id'])) {
					$this->M_npk->prepareMaskCell($_POST['npk_id'], $cols, $rows);
				}
			}
			foreach($_POST['header'] as $k => $v) 
				$this->M_npk->setCellHeader($_POST['npk_id'], $k, $v);
			$ccomp = 0;
			if(isset($_POST['comp'])) {
				$ccomp = count($_POST['comp']);
				foreach($_POST['comp'] as $k => $v) {
					$this->M_npk->setCell($_POST['npk_id'], 1, $k+1, 'LCF', $v);
					$this->M_npk->setCell($_POST['npk_id'], 2, $k+1, 'NOM', $v);
				}
			}
			$j = $ccomp+1;
			foreach($_POST['aggrow'] as $k => $v) {
				$this->M_npk->setCell($_POST['npk_id'], 1, $j, 'LBL', $v);
				$j++;
			}
			$this->M_npk->setStatus($npk_id, 4);
		}
		redirect("/doc/npktbldata/".$npk_id);
	}

	public function npktbldata($npk_id) {
		$this->load->model("M_compfee");
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_doc_npktbl_val";
		$pm["mid-content"]["dt"] = $this->M_npk->getLists("NPK_ID=".$npk_id);
		$pm["mid-content"]["pgl"] = $this->M_pengelola->getLists("PGL_ID=".$pm["mid-content"]["dt"][0]->PGL_ID);
		$pm["mid-content"]["proc"] = $this->M_npk->getProcess($npk_id, 0);
		$pm["mid-content"]["proc_exist"] = $this->M_npk->getProcess($npk_id, 1);
		$pm["mid-content"]["tier"] = $this->M_compfee->getTier();
		$pm["mid-content"]["udef"] = $this->M_compfee->getLists("CF_TYPE='UDEF'");
		$pm["mid-content"]["tbl"] = $this->M_npk->getTableFormat($npk_id);
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}

	public function npktbldatado($npk_id) {
		if(isset($_POST["submit"])) {
			$this->load->model("M_compfee");
			foreach($_POST["cell"] as $k1=>$v1) {
				foreach($v1 as $k2=>$v2) {
					if(trim($v2)!="") {
						if($_POST["typecell"][$k1][$k2] == "LBL") {
							$this->M_npk->setCell($_POST['npk_id'], $k2, $k1, $_POST["typecell"][$k1][$k2], $v2);
						} else {
							$cf = $this->M_compfee->getLists("CF_NAME='".$v2."'");
							if(count($cf) > 0)
								$this->M_npk->setCell($_POST['npk_id'], $k2, $k1, $_POST["typecell"][$k1][$k2], $cf[0]->CF_ID);
						}
					} else {
						$this->M_npk->setCell($_POST['npk_id'], $k2, $k1, "LBL", "");
					}
				}
			}
			$this->M_npk->setStatus($npk_id, 5);
			redirect("/doc/npk/".$npk_id);
		} elseif(isset($_POST["logical"])) {
			redirect("/doc/npktbl/".$npk_id);
		}
	}
	
	public function npksheet($npk_id) {
		if( count($this->M_npk->getTableFormat($npk_id)) > 0) {
			// Change status
			$this->M_npk->setStatus($npk_id, 6);
			
			// Data
			$inttocol = array(1=>"A", 2=>"B", 3=>"C", 4=>"D", 5=>"E", 6=>"F", 7=>"G", 8=>"H", 9=>"I", 10=>"J");
			$npk = $this->M_npk->getLists("NPK_ID=".$npk_id);
			$pgl = $this->M_pengelola->getLists("PGL_ID=".$npk[0]->PGL_ID);
			$proc0 = $this->M_npk->getProcess($npk_id, 0);
			$colnum = $this->M_npk->getColNum($npk_id);
			$rownum = $this->M_npk->getRowNum($npk_id);
			$stepnum = $this->M_npk->getStepNum($npk_id);

			// Sheet
			$this->load->library("phpexcel");
			$filename = "npk_".$npk_id.".xls";
			$this->phpexcel->getProperties()->setCreator("PT Telekomunikasi Indonesia, Tbk")
				 ->setLastModifiedBy("PT Telekomunikasi Indonesia, Tbk")
				 ->setTitle("NPK")
				 ->setKeywords("office 2007 openxml php")
				 ->setCategory("Marketing Fee");
			$this->phpexcel->setActiveSheetIndex(0);
			$sh = & $this->phpexcel->getActiveSheet();
			$sh->setCellValue('A2', 'NOTA PERHITUNGAN KEUANGAN (NPK) MARKETING FEE')
			   ->setCellValue('A3', strtoupper($pgl[0]->PGL_NAME))
			   ->setCellValue('A4', 'PERIODE TAGIHAN : '.strtoupper($this->month_id[substr($npk[0]->PERIOD, 4, 2)]).' '.substr($npk[0]->PERIOD,0,4))
			;
			$sh->mergeCells('A2:'.$inttocol[$colnum].'2')->mergeCells('A3:'.$inttocol[$colnum].'3')->mergeCells('A4:'.$inttocol[$colnum].'4');
			$sh->getStyle('A2')->getFont()->setSize(13);
			$sh->getStyle('A3')->getFont()->setSize(13);
			$sh->getStyle('A4')->getFont()->setSize(11);
			$sh->getStyle('A2:A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sh->getStyle('A2:A4')->getFont()->setBold(TRUE);
			$sh->getStyle('A7:M200')->getFont()->setSize(10);
			$sh->getRowDimension(2)->setRowHeight(16);
			$sh->getRowDimension(3)->setRowHeight(16);
			$sh->getRowDimension(4)->setRowHeight(16);
			
			$x = 7;
			
			$sh->getStyle('A'.$x.':'.$inttocol[$colnum].$x)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$sh->getStyle('A'.$x.':'.$inttocol[$colnum].$x)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$sh->getStyle('A'.$x.':A'.($x+$rownum))->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			$sh->getStyle($inttocol[$colnum].$x.':'.$inttocol[$colnum].($x+$rownum))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			for($i=1; $i<=$rownum; $i++) {
				// Format
				if($i==$rownum)
					$sh->getStyle('A'.($x+$i).':'.$inttocol[$colnum].($x+$i))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
				else 
					$sh->getStyle('A'.($x+$i).':'.$inttocol[$colnum].($x+$i))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_HAIR);
				
				// Data
				$cold = $this->M_npk->getColsData($npk_id, $i);
				for($j=1; $j<=$colnum; $j++) {
					if($cold[$j]["VALUE_AS"]=="LBL") {
						$sh->setCellValue($inttocol[$j].($x+$i), $cold[$j]["CF_LABEL"]);
						$sh->getStyle($inttocol[$j].($x+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
					} else {
							if($cold[$j]["CF_ID"]==116 && ($cold[$j]["VALUE_AS"]=="NOM" || $cold[$j]["VALUE_AS"]=="CUR") ) {
								$sh->setCellValue($inttocol[$j].($x+$i), round($npk[0]->FEE_NON_TAX) );
								$sh->getStyle($inttocol[$j].($x+$i))->getFont()->setBold(TRUE);
							} elseif($cold[$j]["CF_ID"]==117 && ($cold[$j]["VALUE_AS"]=="NOM" || $cold[$j]["VALUE_AS"]=="CUR") ) {
								$sh->setCellValue($inttocol[$j].($x+$i), round($npk[0]->FEE_TAX) );
								$sh->getStyle($inttocol[$j].($x+$i))->getFont()->setBold(TRUE);
							} elseif($cold[$j]["CF_ID"]==118 && ($cold[$j]["VALUE_AS"]=="NOM" || $cold[$j]["VALUE_AS"]=="CUR") ) {
								$sh->setCellValue($inttocol[$j].($x+$i), round($npk[0]->FEE_TOTAL) );
								$sh->getStyle($inttocol[$j].($x+$i))->getFont()->setBold(TRUE);
							} else {
								$sh->setCellValue($inttocol[$j].($x+$i), $this->M_npk->getProcessComView($npk_id, $cold[$j]["CF_ID"], $cold[$j]["VALUE_AS"]) );
							}
							
							if($cold[$j]["VALUE_AS"]=="LCF" || $cold[$j]["VALUE_AS"]=="FML") {
								$sh->getStyle($inttocol[$j].($x+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
							} elseif($cold[$j]["VALUE_AS"]=="NOM") {
								$sh->getStyle($inttocol[$j].($x+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
								$sh->getStyle($inttocol[$j].($x+$i))->getNumberFormat()->setFormatCode('#,##0');
							} elseif($cold[$j]["VALUE_AS"]=="CUR") {
								$sh->getStyle($inttocol[$j].($x+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
								$sh->getStyle($inttocol[$j].($x+$i))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD);
							} elseif($cold[$j]["VALUE_AS"]=="PCT") {
								$sh->getStyle($inttocol[$j].($x+$i))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
							}
					}
				}
			}
			
			for($j=1; $j<$colnum; $j++) {
				$sh->getStyle($inttocol[$j].$x.':'.$inttocol[$j].($x+$rownum))->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			}
			
			// Header
			$headers = $this->M_npk->getColsData($npk_id, 0);
			for($j=1; $j<=$colnum; $j++) {
				$sh->setCellValue($inttocol[$j].$x, $headers[$j]["CF_LABEL"]);
				$sh->getStyle($inttocol[$j].$x)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$sh->getStyle($inttocol[$j].$x)->getFont()->setBold(TRUE);
				if($j==1)
					$sh->getColumnDimension($inttocol[$j])->setWidth(20);
				else 
					$sh->getColumnDimension($inttocol[$j])->setWidth(12);
			}
			
			// Signature Telkom
			$sh->setCellValue('A'.($x+$rownum+5), "PT TELKOM" );
			$sh->setCellValue('A'.($x+$rownum+6), "UNIT ENTERPRISE REGIONAL II" );
			$sh->setCellValue('A'.($x+$rownum+11), $npk[0]->SIGN_NAME_1 );
			$sh->setCellValue('A'.($x+$rownum+12), $npk[0]->SIGN_POS_1 );
			if($colnum>=4) {
				$sh->mergeCells('A'.($x+$rownum+5).':B'.($x+$rownum+5) );
				$sh->mergeCells('A'.($x+$rownum+6).':B'.($x+$rownum+6) );
				$sh->mergeCells('A'.($x+$rownum+11).':B'.($x+$rownum+11) );
				$sh->mergeCells('A'.($x+$rownum+12).':B'.($x+$rownum+12) );
			}
			$sh->getStyle('A'.($x+$rownum+5) )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sh->getStyle('A'.($x+$rownum+6) )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sh->getStyle('A'.($x+$rownum+11) )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sh->getStyle('A'.($x+$rownum+12) )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sh->getStyle('A'.($x+$rownum+11))->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
			
			// Signature Pengelola
			if($colnum>=4) {
				$nstart = $colnum-1;
			} else {
				$nstart = $colnum;
			}
			$sh->setCellValue($inttocol[$nstart].($x+$rownum+3), "Jakarta,    ".$this->month_id[substr($npk[0]->PERIOD, 4, 2)]." ".substr($npk[0]->PERIOD, 0, 4) );
			$sh->setCellValue($inttocol[$nstart].($x+$rownum+5), $pgl[0]->PGL_NAME );
			$sh->setCellValue($inttocol[$nstart].($x+$rownum+11), $npk[0]->SIGN_NAME_2 );
			$sh->setCellValue($inttocol[$nstart].($x+$rownum+12), $npk[0]->SIGN_POS_2 );
			$sh->mergeCells($inttocol[$nstart].($x+$rownum+3).':'.$inttocol[$colnum].($x+$rownum+3) );
			$sh->mergeCells($inttocol[$nstart].($x+$rownum+5).':'.$inttocol[$colnum].($x+$rownum+5) );
			$sh->mergeCells($inttocol[$nstart].($x+$rownum+11).':'.$inttocol[$colnum].($x+$rownum+11) );
			$sh->mergeCells($inttocol[$nstart].($x+$rownum+12).':'.$inttocol[$colnum].($x+$rownum+12) );
			$sh->getStyle($inttocol[$nstart].($x+$rownum+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sh->getStyle($inttocol[$nstart].($x+$rownum+5))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sh->getStyle($inttocol[$nstart].($x+$rownum+11))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sh->getStyle($inttocol[$nstart].($x+$rownum+12))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sh->getStyle($inttocol[$nstart].($x+$rownum+11))->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);

			$objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
			$objWriter->save(dirname(__FILE__).'/../third_party/report/'.$filename);
			redirect($this->config->config['base_url'].'application/third_party/report/'.$filename, 'location', 301);
		} else {
			redirect("/doc/npk");
		}
	}
	
	// Berita Acara
	public function ba() {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_doc_ba";
		$cond = "";
		$this->filtering();
		if(count($this->filters)>1
		&& isset($this->filters["f_pgl_id"]) 
		&& isset($this->filters["f_period_m"]) 
		&& isset($this->filters["f_period_y"]) ) {
			if($this->filters["f_pgl_id"]!="") $cond .= " AND PGL_ID=".$this->filters["f_pgl_id"];
			if($this->filters["f_period_m"]!="" && $this->filters["f_period_y"]!="") $cond .= " AND PERIOD='".$this->filters["f_period_y"].$this->filters["f_period_m"]."'";
		}
		$pm["mid-content"]["dt"] = $this->M_npk->getLists("STATUS IN(3,4,5,6,7,9)".$cond);
		foreach($this->M_pengelola->getLists("ENABLE_FEE='1'") as $k => $v) 
			$pm["mid-content"]["pgl"][$v->PGL_ID] = $v->PGL_NAME;
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}

	public function basetmou($npk_id) {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_doc_ba_mou";
		$pm["mid-content"]["dt"] = $this->M_npk->getLists("NPK_ID=".$npk_id);
		$pm["mid-content"]["ba"] = $this->M_npk->getPengantarBAAttr($npk_id);
		foreach($this->M_pengelola->getLists() as $k => $v) 
			$pm["mid-content"]["pgl"][$v->PGL_ID] = $v->PGL_NAME;
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}

	public function basetmoudo() {
		if(isset($_POST["submit"])) {
			$this->M_npk->setMOU($_POST['npk_id'], $_POST['mou_no'], $_POST['mou_date']);
			$this->M_npk->setPengantarBAAttr($_POST['npk_id'], $_POST['am_name'], $_POST['am_pos'], $_POST['data_source'], 
				$_POST['ubc_signer_name'], $_POST['ubc_signer_nik'], $_POST['ubc_signer_pos'], 
				$_POST['gs_signer_name'], $_POST['gs_signer_nik'], $_POST['gs_signer_pos']);
			redirect("/doc/badoc/".$_POST['npk_id']);
		} else {
			redirect("/doc/ba");
		}
	}
	
	public function badoc($npk_id) {
		$npk = $this->M_npk->getLists("NPK_ID=".$npk_id);
		$ba  = $this->M_npk->getPengantarBAAttr($npk_id);
		if(!isset($npk[0]->MOU_NO) || $npk[0]->MOU_NO=="" || count($ba)==0 || 
			$ba[0]->AM_NAME=="" || $ba[0]->AM_POS=="" || $ba[0]->DATA_SOURCE=="" || 
			$ba[0]->UBC_SIGNER_NAME=="" || $ba[0]->UBC_SIGNER_POS=="" || $ba[0]->UBC_SIGNER_NIK=="" ||
			$ba[0]->GS_SIGNER_NAME=="" || $ba[0]->GS_SIGNER_POS=="" || $ba[0]->GS_SIGNER_NIK=="" 
		) {
			redirect("/doc/basetmou/".$npk_id);
		} else {
			if( count($this->M_npk->getTableFormat($npk_id)) > 0) {
					// Change status
					$this->M_npk->setStatus($npk_id, 7);

					$this->load->library("clsmsdocgenerator");
					$this->load->library("terbilang");

					$pgl = $this->M_pengelola->getLists("PGL_ID=".$npk[0]->PGL_ID);
					$mfee_terbilang = $this->terbilang->eja(round($npk[0]->FEE_TOTAL));
					$mou_date = explode("/", $npk[0]->MOU_DATE);
					$prevperiod = $this->M_npk->getPrevPeriod($npk[0]->PERIOD);

					// Hal : 1
					$this->clsmsdocgenerator->newSession("PORTRAIT", "LETTER", 2.5, 3, 2.5, 3);
					$this->clsmsdocgenerator->setFontFamily("Times New Roman");
					$this->clsmsdocgenerator->setFontSize(11);
					$this->clsmsdocgenerator->addParagraph("&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>");
					$this->clsmsdocgenerator->addParagraph("PERHITUNGAN MARKETING FEE<br>".$pgl[0]->PGL_NAME, 
						array('text-align' => 'center', 'font-weight' => 'bold', 'font-size'=>'12pt') );
					$this->clsmsdocgenerator->startTable(array('width'=>'100%'), '');
					$this->clsmsdocgenerator->addTableRow(array('&nbsp;'), array('center'), array('middle'), 
						array('font-size'=>'2pt', 'border-bottom'=>'4px double black'));
					$this->clsmsdocgenerator->endTable();
					$this->clsmsdocgenerator->addParagraph("<br>");
					$this->clsmsdocgenerator->addParagraph("Dengan ini kami informasikan bahwa hasil perhitungan <b>Marketing Fee ".
						$pgl[0]->PGL_NAME."</b> untuk tagihan bulan ".$this->month_id[substr($npk[0]->PERIOD,4,2)]." ".
						substr($npk[0]->PERIOD,0,4)." (pemakaian ".$this->month_id[substr($prevperiod,4,2)]." ".
						substr($prevperiod,0,4).") sesuai data yang disampaikan oleh Sdr. ".$ba[0]->AM_NAME." ".
						"selaku ".$ba[0]->AM_POS." <b>".$pgl[0]->PGL_NAME."</b> dari pengecekan di ".$ba[0]->DATA_SOURCE.
						" dengan rincian sebagai berikut : ", 
						array('text-align'=>'justify'));
					$this->clsmsdocgenerator->addParagraph("");
					
					// Table Data
					$nrow = $this->M_npk->getRowNum($npk_id);
					$dcol1 = $this->M_npk->getRowsData($npk_id, 1);
					$dcol2 = $this->M_npk->getRowsData($npk_id, 2);
					$aligns = array('left', 'right');
					$valigns = array('middle', 'middle');
					$this->clsmsdocgenerator->startTable(array('margin-left'=>'30px', 'font-size'=>'10pt', 'padding'=>'2px', 'width'=>'450px'), '');
					$this->clsmsdocgenerator->addTableRow(array("<b>KOMPONEN TAGIHAN</b>", "<b>TAGIHAN</b>" ), array('center', 'center'), $valigns, array('border'=>'1px solid black', 'border-bottom'=>'1px solid black') );
					$jml = 0;
					for($i=1; $i<=$nrow; $i++) {
						if($dcol1[$i]['VALUE_AS']!="LCF") break;
						$r1 = $this->M_npk->getProcessComView($npk_id, $dcol1[$i]['CF_ID'], $dcol1[$i]['VALUE_AS']);
						$r2 = $this->M_npk->getProcessComView($npk_id, $dcol2[$i]['CF_ID'], $dcol2[$i]['VALUE_AS']);
						$jml += $r2;
						$this->clsmsdocgenerator->addTableRow(array("<b>".$r1."</b>", "Rp. ".number_format($r2) ), $aligns, $valigns, 
                                                            array('border'=>'1px solid black', 'border-bottom'=>'0px') );
					}
					$this->clsmsdocgenerator->addTableRow(array("<b>Jumlah</b>", "<b>"."Rp. ".number_format($jml)."</b>" ), array('left', 'right'), $valigns, array('border'=>'1px solid black', 'border-top'=>'2px solid black') );
					$this->clsmsdocgenerator->endTable();
					
					$this->clsmsdocgenerator->addParagraph("&nbsp;");
					$this->clsmsdocgenerator->addParagraph("Demikian Marketing Fee ini kami buat untuk digunakan sebagaimana mestinya.", 
						array('text-align'=>'justify'));
					$this->clsmsdocgenerator->addParagraph("&nbsp;");
					
					$this->clsmsdocgenerator->startTable(array('font-size'=>'11pt', 'font-weight'=>'bold'), '');
					$rowd_1 = array($ba[0]->UBC_SIGNER_POS, '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $ba[0]->GS_SIGNER_POS );
					$rowd_2 = array('&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>', '', '');
					$rowd_3 = array('<u>'.$ba[0]->UBC_SIGNER_NAME.'</u><br>'.$ba[0]->UBC_SIGNER_NIK, '', '<u>'.$ba[0]->GS_SIGNER_NAME.'</u><br>'.$ba[0]->GS_SIGNER_NIK);
					$aligns = array('center', 'center', 'center');
					$valigns = array('middle', 'middle', 'middle');
					$this->clsmsdocgenerator->addTableRow($rowd_1, $aligns, $valigns, array());
					$this->clsmsdocgenerator->addTableRow($rowd_2, $aligns, $valigns, array());
					$this->clsmsdocgenerator->addTableRow($rowd_3, $aligns, $valigns, array());
					$this->clsmsdocgenerator->endTable();
					
					// Hal : 2
					//$this->clsmsdocgenerator->newPage();
					$this->clsmsdocgenerator->newSession("PORTRAIT", "LETTER", 2.5, 3, 2.5, 3);
					$this->clsmsdocgenerator->setFontFamily("Times New Roman");
					$this->clsmsdocgenerator->setFontSize(11);
					
					$this->clsmsdocgenerator->addParagraph("&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>");
					$this->clsmsdocgenerator->addParagraph("BERITA ACARA<br>PEMBAYARAN MARKETING FEE<br>".$pgl[0]->PGL_NAME, 
						array('text-align' => 'center', 'font-weight' => 'bold', 'font-size'=>'12pt') );
					$this->clsmsdocgenerator->startTable(array('width'=>'100%'), '');
					$this->clsmsdocgenerator->addTableRow(array('&nbsp;'), array('center'), array('middle'), 
						array('font-size'=>'2pt', 'border-bottom'=>'4px double black'));
					$this->clsmsdocgenerator->endTable();
					$this->clsmsdocgenerator->addParagraph("<br>NO.C.TEL.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".
							"/KUG.000/DES-K2000000 / ".date("Y")."<br>&nbsp;<br>&nbsp;", 
						array('text-align' => 'center', 'font-size'=>'12pt') );
					$this->clsmsdocgenerator->addParagraph("Pada hari ini, ............tanggal.............bulan............tahun ".
						"<b>".ucwords($this->terbilang->eja(date("Y")))."</b>, Kami yang bertanda tangan di bawah ini :", 
						array('text-align'=>'justify'));
					$this->clsmsdocgenerator->addParagraph("");
					
					$this->clsmsdocgenerator->startTable(array('margin-left'=>'25px', 'font-size'=>'11pt'), '');
					$rowd_1 = array('1.&nbsp;&nbsp;', 'Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', ':&nbsp;&nbsp;&nbsp;', $npk[0]->SIGN_NAME_1);
					$rowd_2 = array('', 'Jabatan', ':', $npk[0]->SIGN_POS_1);
					$aligns = array('left', 'left', 'left', 'left');
					$valigns = array('middle', 'middle', 'middle', 'middle');
					$this->clsmsdocgenerator->addTableRow($rowd_1, $aligns, $valigns, array());
					$this->clsmsdocgenerator->addTableRow($rowd_2, $aligns, $valigns, array());
					$this->clsmsdocgenerator->endTable();
					$this->clsmsdocgenerator->addParagraph("");

					$this->clsmsdocgenerator->startTable(array('margin-left'=>'25px', 'font-size'=>'11pt'), '');
					$rowd_3 = array('2.&nbsp;&nbsp;', 'Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', ':&nbsp;&nbsp;&nbsp;', $npk[0]->SIGN_NAME_2);
					$rowd_4 = array('', 'Jabatan', ':', $npk[0]->SIGN_POS_2);
					$aligns = array('left', 'left', 'left', 'left');
					$valigns = array('middle', 'middle', 'middle', 'middle');
					$this->clsmsdocgenerator->addTableRow($rowd_3, $aligns, $valigns, array());
					$this->clsmsdocgenerator->addTableRow($rowd_4, $aligns, $valigns, array());	
					$this->clsmsdocgenerator->endTable();

					$this->clsmsdocgenerator->addParagraph("");
					$this->clsmsdocgenerator->addParagraph("Telah melakukan perhitungan Marketing Fee Telkom untuk ".
						$pgl[0]->PGL_NAME." sesuai PKS No. ".$npk[0]->MOU_NO." tanggal ".$mou_date[0]." ".$this->month_id[ $mou_date[1] ]." ".$mou_date[2]." ".
						"untuk tagihan bulan ".$this->month_id[substr($npk[0]->PERIOD,4,2)]." ".substr($npk[0]->PERIOD,0,4).
						" (pemakaian ".$this->month_id[substr($prevperiod,4,2)]." ".substr($prevperiod,0,4).") ".
						"dengan total nilai sebesar <b>Rp ".number_format($npk[0]->FEE_TOTAL)."</b> ".
						"(<b><i>".ucfirst($mfee_terbilang)." rupiah</i></b>) setelah PPN 10 %  ".
						"dengan rincian sebagai berikut:", 
						array('text-align'=>'justify'));
					$this->clsmsdocgenerator->addParagraph("");
					$this->clsmsdocgenerator->startTable(array('font-size'=>'11pt'), '');
					$rowd_1 = array('Jumlah Marketing Fee sebelum PPN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 
						'<b>Rp</b>&nbsp;&nbsp;&nbsp;&nbsp;', "<b>".number_format($npk[0]->FEE_NON_TAX,0 , ".", ",")."</b>" );
					$rowd_2 = array('PPN 10% dari jumlah Marketing Fee', '<b>Rp</b>', "<b>".number_format($npk[0]->FEE_TAX,0 , ".", ",")."</b>" );
					$rowd_3 = array('Jumlah Marketing Fee sesudah PPN', '<b>Rp</b>', "<b>".number_format($npk[0]->FEE_TOTAL,0 , ".", ",")."</b>" );
					$aligns = array('left', 'left', 'right');
					$valigns = array('middle', 'middle', 'middle');
					$this->clsmsdocgenerator->addTableRow($rowd_1, $aligns, $valigns, array());
					$this->clsmsdocgenerator->addTableRow($rowd_2, $aligns, $valigns, array());
					$this->clsmsdocgenerator->addTableRow($rowd_3, $aligns, $valigns, array());
					$this->clsmsdocgenerator->endTable();
					$this->clsmsdocgenerator->addParagraph("");
					$this->clsmsdocgenerator->addParagraph("Demikian Berita Acara ini dibuat sebagai dasar pelaksanaan pembayaran Marketing Fee.");
					$this->clsmsdocgenerator->addParagraph("<br><br><br>");

					$this->clsmsdocgenerator->startTable(array('font-size'=>'11pt', 'font-weight'=>'bold'), '');
					$rowd_1 = array('PT.TELKOM', '', $pgl[0]->PGL_NAME );
					$rowd_2 = array('UNIT ENTERPRISE REGIONAL II', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', '' );
					$rowd_3 = array('&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;<br>', '', '');
					$rowd_4 = array('<u>'.$npk[0]->SIGN_NAME_1.'</u>', '', '<u>'.$npk[0]->SIGN_NAME_2.'</u>');
					$rowd_5 = array($npk[0]->SIGN_POS_1, '', $npk[0]->SIGN_POS_2 );
					$aligns = array('center', 'center', 'center');
					$valigns = array('middle', 'middle', 'middle');
					$this->clsmsdocgenerator->addTableRow($rowd_1, $aligns, $valigns, array());
					$this->clsmsdocgenerator->addTableRow($rowd_2, $aligns, $valigns, array());
					$this->clsmsdocgenerator->addTableRow($rowd_3, $aligns, $valigns, array());
					$this->clsmsdocgenerator->addTableRow($rowd_4, $aligns, $valigns, array());
					$this->clsmsdocgenerator->addTableRow($rowd_5, $aligns, $valigns, array());
					$this->clsmsdocgenerator->endTable();

					$this->clsmsdocgenerator->output();
			} else {
				redirect("/doc/ba");
			}
		}

	}
	
	public function loadtbl($npk_id) {
		$this->load->view('v_head');
		$ct["mid-menu"] = "v_menu";
		$ct["mid-content"] = "v_npkloadtbl";
		$pm["mid-content"]["dt"] = $this->M_npk->getLists("NPK_ID=".$npk_id);
		$pm["mid-content"]["pgl"] = $this->M_pengelola->getLists("PGL_ID=".$pm["mid-content"]["dt"][0]->PGL_ID);
		$pm["mid-content"]["periods"] = array();
		foreach($this->M_npk->getRecent() as $k=>$v) 
			$pm["mid-content"]["rc"][$v->NPK_RC_ID] = $v->NPK_RC_NAME;
		foreach($this->M_npk->getLists("PGL_ID=".$pm["mid-content"]["dt"][0]->PGL_ID." AND PERIOD<>'".$pm["mid-content"]["dt"][0]->PERIOD."'") as $k=>$v) 
			$pm["mid-content"]["periods"][$v->NPK_ID] = $v->PERIOD;
		$this->load->view('v_body', array("ct"=>$ct, "pm"=>$pm) );
	}

	public function loadtbldo($npk_id) {
		if(isset($_POST["submit"])) {
			$this->M_npk->loadTableFormat($_POST['npk_rc_id'], $_POST['npk_id']);
		}
		redirect("/doc/npktbl/".$npk_id);
	}
	
}
?>