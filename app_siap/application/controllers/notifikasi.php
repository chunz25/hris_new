<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class notifikasi extends SIAP_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	public function getShortNotification()
	{
		$penerimaid = $this->session->userdata('pegawaiid');
		$mnotif = $this->tp_notification->getShortNotif($penerimaid);
		$notifUnread = $this->tp_notification->getCountNotifUnread($penerimaid);
		$result = array('success' => true, 'count' => $notifUnread, 'data' => $mnotif);
		echo json_encode($result);
	}

	// Editing by Tama
	public function getShortNotificationCuti()
	{
		$penerimaid = $this->session->userdata('pegawaiid');
		$mnotif = $this->tp_notification->getShortNotifCuti($penerimaid);
		$notifUnread = $this->tp_notification->getCountNotifUnread($penerimaid);
		$result = array('success' => true, 'count' => $notifUnread, 'data' => $mnotif);
		echo json_encode($result);
	}

	public function getShortNotificationDinas()
	{
		$penerimaid = $this->session->userdata('pegawaiid');
		$mnotif = $this->tp_notification->getShortNotifDinas($penerimaid);
		$notifUnread = $this->tp_notification->getCountNotifUnread($penerimaid);
		$result = array('success' => true, 'count' => $notifUnread, 'data' => $mnotif);
		echo json_encode($result);
	}
	// End Editing by Tama

	public function updateReadNotif()
	{
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
