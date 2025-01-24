<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class notifikasi extends EServices_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_pengajuan');
	}

	public function index()
	{
		$this->getAllNotifikasi();
	}

	public function getAllNotifikasi()
	{
		// $this->m_pengajuan->addlogs();
		$data = array();
		$data['pages'] = "notifikasi";
		$content = "notifikasi/v_notifikasi";

		$this->load->view($content, $data);
	}

	public function getAllNotification()
	{
		// $this->m_pengajuan->addlogs();
		$row = $this->input->get('start');
		$penerimaid = $this->session->userdata('pegawaiid');
		$mnotif = $this->tp_notification->getAllNotification($penerimaid, $row);
		$notifCount = $this->tp_notification->getCountNotif($penerimaid);
		$result = array('success' => true, 'count' => $notifCount, 'data' => $mnotif);
		echo json_encode($result);
	}

	public function getAllNotificationHR()
	{
		// $this->m_pengajuan->addlogs();
		$penerimaid = $this->session->userdata('pegawaiid');
		$row = $this->input->get('start');
		$nik = $this->session->userdata('nik');
		$mnotif = $this->tp_notification->getAllNotificationHR($penerimaid, $row);
		$notifCount = $this->tp_notification->getCountNotif($penerimaid);
		$result = array('success' => true, 'count' => $notifCount, 'data' => $mnotif);
		echo json_encode($result);
	}

	public function getShortNotification()
	{
		$penerimaid = $this->session->userdata('pegawaiid');
		$mnotif = $this->tp_notification->getShortNotif($penerimaid);
		$notifUnread = $this->tp_notification->getCountNotifUnread($penerimaid);
		$result = array('success' => true, 'count' => $notifUnread, 'data' => $mnotif);
		echo json_encode($result);
	}

	public function getShortNotificationHR()
	{
		$penerimaid = $this->session->userdata('pegawaiid');
		$nik = $this->session->userdata('nik');
		$mnotif = $this->tp_notification->getShortNotifHR($penerimaid, $nik);
		$notifUnread = $this->tp_notification->getCountNotifUnread($penerimaid);
		$result = array('success' => true, 'count' => $notifUnread, 'data' => $mnotif);
		echo json_encode($result);
	}

	public function updateReadNotif()
	{
		// $this->m_pengajuan->addlogs();
		$penerimaid = $this->session->userdata('pegawaiid');
		$mnotif = $this->tp_notification->updateNotifRead($penerimaid);
		if ($mnotif) {
			$result = array('success' => true);
		} else {
			$result = array('success' => false);
		}
		echo json_encode($result);
	}

}

