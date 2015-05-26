<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class asyncreq extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('M_profiling');
		$this->load->model('M_pengelola');
		$this->load->model('M_tenant');
	}
	
	public function dropdownten($pgl_id) {
		$rec = $this->M_tenant->getFromPgl($pgl_id);
		$data = array(""=>"-- Select Tenant --"); 
		foreach($rec as $k=>$v)
			$data[$v->TEN_ID] = $v->TEN_NAME;
		echo form_dropdown('ten_id', $data, array(), "class='ten_of_pgl'");
	}
	
	public function dropdownperiodten($ten_id) {
		$rec = $this->M_tenant->NDHistPeriod($ten_id);
		$data = array(""=>"-- Select Period --"); 
		foreach($rec as $k=>$v)
			$data[$v->PERIOD] = $v->PERIOD;
		echo form_dropdown('period', $data, array());
	}

}

?>