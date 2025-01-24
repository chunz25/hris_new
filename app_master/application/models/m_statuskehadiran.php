<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class m_statuskehadiran extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function getStatusKehadiran()
	{
		$this->load->database();
		$q = $this->db->query("select id, name as text from kehadiran.absensi_status order by id");
		$this->db->close();
		return $q->result_array();
	}
}