<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class m_agama extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function get_agama()
	{
		$this->load->database();
		$q = $this->db->query("SELECT id, name AS text FROM agama ORDER BY id");
		$this->db->close();
		return $q->result_array();
	}
}