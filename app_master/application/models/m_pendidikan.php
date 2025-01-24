<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class m_pendidikan extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function getDataPendidikan()
	{
		$this->load->database();
		$q = $this->db->query("SELECT id, code AS text, name as keterangan FROM pendidikan ORDER BY id");
		return $q->result_array();
	}
}