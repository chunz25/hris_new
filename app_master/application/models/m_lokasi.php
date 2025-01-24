<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class m_lokasi extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function getmasterlokasi($node)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT id, name as text FROM lokasi ORDER BY name
		", array($node));
		$this->db->close();
		return $q;
	}

	function getLokasiKerja()
	{
		$this->load->database();
		$q = $this->db->query("SELECT id, name as text, code as kodelokasi FROM lokasi ORDER BY name ASC");
		$this->db->close();
		return $q->result_array();
	}

	function tambah($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			INSERT INTO lokasi(id, name, code)
			SELECT COALESCE(MAX(id)+1,1),?,?
			FROM lokasi
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function ubah($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			UPDATE lokasi SET name = ?, code = ? WHERE id = ?
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function hapus($id)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("DELETE FROM lokasi WHERE id = ?", array($id));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
}