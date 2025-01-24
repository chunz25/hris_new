<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class tp_notifikasi
{
	private $CI;
	function __construct()
	{
		$this->CI =& get_instance();
	}
	function send_notifikasi($params)
	{
		$cek = $this->cek_notif($params['id_orders'], $params['id_orderssup'], $params['userid'], $params['statusid']);
		$mresult = false;
		if ($cek == 0) {
			$mresult = $this->tambah_notif($params);
		}
		return $mresult;
	}
	function tambah_notif($params)
	{
		$this->CI->load->database();
		$this->CI->db->trans_start();
		$q = $this->CI->db->query("
			INSERT INTO kehadiran.cuti_notifikasi(userid,tgl_notif,jenis,id_orders,no_resi,keterangan,userid_from, statusid, id_orderssup)
			VALUES(?,NOW(),?,?,?,?,?,?,?)			
		", $params);
		$this->CI->db->trans_complete();
		$this->CI->db->close();
		return $this->CI->db->trans_status();
	}
	function cek_notif($noorders, $noorders_sup, $userid, $status)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("SELECT COUNT(*) AS jml FROM notifikasi WHERE id_orders = ? AND id_orderssup = ? AND userid = ? AND statusid = ?", array($noorders, $noorders_sup, $userid, $status));
		$this->CI->db->close();
		return $q->first_row()->jml;
	}
}