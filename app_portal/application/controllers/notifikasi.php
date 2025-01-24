<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class notifikasi extends Portal_Controller{
	function __construct(){
		parent::__construct();		
	}	
	
	public function index(){
		$this->getAllNotifikasi();
	}

	public function getShortNotification(){
		$params = array(
			'v_penerimaid' => $this->session->userdata('pegawaiid'),
		);
		$mnotif = $this->tp_portalnotification->getShortNotif($params);
		$notifUnread = $this->tp_portalnotification->getCountNotifUnread($params);
		$result = array('success'=>true, 'count'=>$notifUnread, 'data'=>$mnotif);		
		echo json_encode($result);
	}

	public function getShortNotificationHR(){
		$params = array(
			'v_penerimaid' => $this->session->userdata('pegawaiid'),
		);
		$mnotif = $this->tp_portalnotification->getShortNotifHR($params);
		$notifUnread = $this->tp_portalnotification->getCountNotifUnread($params);
		$result = array('success'=>true, 'count'=>$notifUnread, 'data'=>$mnotif);		
		echo json_encode($result);
	}	
}

