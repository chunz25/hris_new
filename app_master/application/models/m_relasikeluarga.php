<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class m_relasikeluarga extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function getRelasiKeluarga()
	{
		$this->load->database();
		$q = $this->db->query("select id, name as text from relasi order by id");
		return $q->result_array();
	}
}