<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class m_statuscuti extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function getStatusCuti()
	{
		$this->load->database();
		$q = $this->db->query("select id, name as text from kehadiran.cuti_status order by id");
		$this->db->close();
		return $q->result_array();
	}
}