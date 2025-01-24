<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class m_unitkerja extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function get_unitkerja($node)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT 
				id as satkerid ,
				code as id ,
				name as text ,
				unitkerja ,
				direktorat ,
				divisi ,
				departemen ,
				seksi ,
				subseksi ,
				null as kepalanama
			FROM struktur.vw_satkertree
			WHERE (LENGTH(code) = LENGTH(?) + 3 OR LENGTH(code) = LENGTH(?) + 2) AND code LIKE ? || '%'
		", array($node, $node, $node));
		$this->db->close();
		return $q;
	}

	function tambah($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("SELECT struktur.sp_addsatker(?,?);", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function ubah($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("UPDATE struktur.department SET name = ? WHERE id = ?", array($params['v_text'], $params['v_node']));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function hapus($satkerid)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("DELETE FROM struktur.department WHERE id = ?", array($satkerid));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
}
