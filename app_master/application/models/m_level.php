<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class m_level extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function getLevel()
	{
		$this->load->database();
		$q = $this->db->query("SELECT id, name AS text, gol, susunan FROM struktur.levelgrade ORDER BY susunan");
		return $q->result_array();
	}

	function tambah($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("INSERT INTO struktur.levelgrade (id, name, gol) VALUES(?,?,?)", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function ubah($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("UPDATE struktur.levelgrade SET name = ?, gol = ? WHERE id = ?", array($params['level'], $params['gol'], $params['id']));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	
	function hapus($id)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("DELETE FROM struktur.levelgrade WHERE id = ?", array($id));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
}