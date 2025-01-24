<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class m_statusnikah extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function getStatusNikah()
	{
		$this->load->database();
		$q = $this->db->query("select id, name as text from public.statusnikah order by id");
		return $q->result_array();
	}
	function tambah($text)
	{
		$this->load->database();
		$this->db->trans_start();
		$id = $this->db->query("SELECT COALESCE(MAX(id)+1,1) AS id FROM statusnikah")->first_row()->id;
		$q = $this->db->query("INSERT INTO statusnikah(id, name) VALUES(?,?)", array($id, $text));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
}