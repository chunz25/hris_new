<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class template extends Policies_Controller{
	function __construct(){
		parent::__construct();		
		$this->load->model('m_template');
		$this->load->model('m_pengajuan');
	}	
	
	public function index(){
		$this->historypolicies();
	}
	
	public function historypolicies(){
		$data = array();
		$data['infopegawai'] = $this->getInfoPegawai();
		$data['vpegawaiid'] = $this->session->userdata('pegawaiid');
		$data['vsatker'] = $this->m_pengajuan->getComboDivisi();	
		$data['vsatkerid'] = $this->session->userdata('satkerid');
		$data['pages'] = "template";
		$content = "template/v_historytemphome";
		$this->load->view($content,$data);
	}	
	
	public function edit(){
		$params = array(
			'v_id' => ifunsetemptybase64($_GET,'id',null),
		);
		$mGetDetailPolicies = $this->m_template->getPoliciesById($params);
		
		$data = array();
		$data['info'] = $mGetDetailPolicies;
		$data['vpegawaiid'] = $this->session->userdata('pegawaiid');
		$data['vnamadokumen'] = $this->m_template->getComboNamaDokumen();	
		$data['vsatker'] = $this->m_pengajuan->getComboDivisi();	
		$data['pages'] = "template";
		$content = "pengajuan/dinas/v_edit";
		$this->load->view($content,$data);
	}

	public function deleteDraft() {
		$params = array(
			'v_id' => ifunsetempty($_POST,'id',null),
		);
		
		$mresult = $this->m_template->deleteDraft($params);		
		if($mresult){
			$result = array('success' => true, 'message' => 'Data berhasil dihapus yes');
		}
		else{
			$result = array('success' => false, 'message' => 'Data gagal dihapus');
		}		
		echo json_encode($result);
	}

	public function getListPolicies(){
		$akesesid = $this->session->userdata('aksesid_policies');
		$lokasiid = ifunsetempty($_GET,'lokasikerja','');
		$dokumenid = ifunsetempty($_GET,'dokumenid','');
		$id = '';
		// jika admin HR
		if($akesesid == '5'){
			$id = '1';
			$lokasiid = '';
		} else {
			// jika HO maka select 1, DC select 2 else ALL 
			if($lokasiid != '1') {
				$lokasiid = '2';
			}
		}
		$params = array(
			'v_id' => $id,
			'v_keyword' => ifunsetempty($_GET,'keyword',''),
			'v_jenisid' => ifunsetempty($_GET,'newsatker',''),
			'v_jenisform' => '2',
			'v_lokasiid' => $lokasiid,
			'v_dokumenid' => $dokumenid,
			'v_start' => ifunsetempty($_GET,'start',0),
			'v_limit' => ifunsetempty($_GET,'limit',config_item('PAGESIZE')),
		);		
		$mresult = $this->m_template->getListPolicies($params);
		echo json_encode($mresult);
	}

	function detaildokumen() {
		$data = array();
		$no = ifunsetemptybase64($_GET,'files',null);

		$data['pages'] = "template";
		$data['no'] = $no;
		$content = "template/v_viewpdf";
		
		$this->load->view($content,$data);	
	}

	public function getInfoPegawai() {
		$params = array(
			'v_pegawaiid' => $this->session->userdata('pegawaiid'),
			'v_tahun' => date("Y")
		);
		
		$mresult = $this->m_template->getInfoPegawai($params);
		return $mresult['firstrow'];
	}
}

