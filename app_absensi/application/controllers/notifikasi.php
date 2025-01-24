<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class notifikasi extends Wfh_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('M_pengajuan');
	}

	public function index()
	{
		$this->getAllNotifikasi();
	}

	public function getAllNotifikasi()
	{
		$data = array();
		$content = "notifikasi/v_notifikasi";
		$data['pages'] = "notifikasi";
		$this->load->view($content, $data);
	}

	public function getAllNotification()
	{
		$row = $this->input->get('start');
		$penerimaid = $this->session->userdata('pegawaiid');
		$mnotif = $this->M_pengajuan->getAllNotification($penerimaid, $row);
		$notifCount = $this->M_pengajuan->getCountNotif($penerimaid);
		$result = array('success' => true, 'count' => $notifCount, 'data' => $mnotif);
		echo json_encode($result);
	}

	public function getAllNotificationHR()
	{
		$penerimaid = $this->session->userdata('pegawaiid');
		$row = $this->input->get('start');
		$nik = $this->session->userdata('nik');
		$mnotif = $this->M_pengajuan->getAllNotificationHR($penerimaid, $nik, $row);
		$notifCount = $this->M_pengajuan->getCountNotif($penerimaid);
		$result = array('success' => true, 'count' => $notifCount, 'data' => $mnotif);
		echo json_encode($result);
	}

	public function getShortNotification()
	{
		$penerimaid = $this->session->userdata('pegawaiid');
		$mnotif = $this->M_pengajuan->getShortNotif($penerimaid);
		$notifUnread = $this->M_pengajuan->getCountNotifUnread($penerimaid);
		$result = array('success' => true, 'count' => $notifUnread, 'data' => $mnotif);
		echo json_encode($result);
	}

	public function getShortNotificationHR()
	{
		$penerimaid = $this->session->userdata('pegawaiid');
		$nik = $this->session->userdata('nik');
		$mnotif = $this->M_pengajuan->getShortNotifHR($penerimaid, $nik);
		$notifUnread = $this->M_pengajuan->getCountNotifUnread($penerimaid);
		$result = array('success' => true, 'count' => $notifUnread, 'data' => $mnotif);
		echo json_encode($result);
	}

	public function updateReadNotif()
	{
		$penerimaid = $this->session->userdata('pegawaiid');
		$mnotif = $this->M_pengajuan->updateNotifRead($penerimaid);
		if ($mnotif) {
			$result = array('success' => true);
		} else {
			$result = array('success' => false);
		}
		echo json_encode($result);
	}
}
