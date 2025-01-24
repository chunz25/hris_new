<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class history extends Policies_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_history');
		$this->load->model('m_pengajuan');
	}

	public function index()
	{
		$this->historypolicies();
	}

	public function historypolicies()
	{
		$data = array();
		$data['infopegawai'] = $this->getInfoPegawai();
		$data['vpegawaiid'] = $this->session->userdata('pegawaiid');
		$data['vsatker'] = $this->m_pengajuan->getComboDivisi();
		$data['vsatkerid'] = $this->session->userdata('satkerid');
		$data['vnamadokumen'] = $this->m_history->getComboNamaDokumen();
		$data['pages'] = "history";
		$content = "history/v_historypolicieshome";
		$this->load->view($content, $data);
	}

	public function edit()
	{
		$params = array(
			'v_id' => ifunsetemptybase64($_GET, 'id', null),
		);
		$mGetDetailPolicies = $this->m_history->getPoliciesById($params);
		// die(var_dump($mGetDetailPolicies));

		$data = array();
		$data['info'] = $mGetDetailPolicies;
		$data['vpegawaiid'] = $this->session->userdata('pegawaiid');
		$data['vnamadokumen'] = $this->m_history->getComboNamaDokumen();
		$data['vsatker'] = $this->m_pengajuan->getComboDivisi();
		$data['pages'] = "history";
		$content = "pengajuan/edit/v_edit";
		$this->load->view($content, $data);
	}

	public function deleteDraft()
	{
		$params = array(
			'v_id' => ifunsetempty($_POST, 'id', null),
		);

		$mresult = $this->m_history->deleteDraft($params);
		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil dihapus yes');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dihapus');
		}
		echo json_encode($result);
	}

	public function getListPolicies()
	{
		$akesesid = $this->session->userdata('admin');
		$lokasiid = ifunsetempty($_GET, 'lokasikerja', '');
		$dokumenid = ifunsetempty($_GET, 'dokumenid', '');
		$id = '';
		// jika admin HR
		if ($akesesid) {
			$id = '1';
			$lokasiid = '';
		} else {
			// jika HO maka select 1, DC select 2 else ALL 
			if ($lokasiid != '1') {
				$lokasiid = '2';
			}
		}
		$params = array(
			'v_id' => $id,
			'v_keyword' => ifunsetempty($_GET, 'keyword', ''),
			'v_jenisid' => ifunsetempty($_GET, 'newsatker', ''),
			'v_jenisform' => '1',
			'v_lokasiid' => $lokasiid,
			'v_dokumenid' => $dokumenid,
			'v_start' => ifunsetempty($_GET, 'start', 0),
			'v_limit' => ifunsetempty($_GET, 'limit', config_item('PAGESIZE')),
		);
		$mresult = $this->m_history->getListPolicies($params);
		echo json_encode($mresult);
	}

	function detaildokumen()
	{
		$data = array();
		$no = ifunsetemptybase64($_GET, 'files', null);

		$data['pages'] = "history";
		$data['no'] = $no;
		$content = "history/v_viewpdf";

		$this->load->view($content, $data);
	}

	public function getInfoPegawai()
	{
		$params = array(
			'v_pegawaiid' => $this->session->userdata('pegawaiid'),
			'v_tahun' => date("Y")
		);

		$mresult = $this->m_history->getInfoPegawai($params);
		return $mresult['firstrow'];
	}
}

