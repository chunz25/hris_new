<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class m_jabatan extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getmasterjabatan($node)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT 
				j.id as jabatanid , 
				j.name as jabatan , 
				null as tipejabatan
			FROM struktur.jabatan j
			WHERE j.name like '$node%'
			ORDER BY j.name
		");
		$this->db->close();
		return $q;
	}

	function tambah($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("SELECT struktur.sp_addjabatan(?,?)", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function ubah($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("UPDATE struktur.jabatan SET name = ? WHERE id = ?", array($params['v_text'], $params['v_node']));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function hapus($satkerid)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("DELETE FROM struktur.jabatan WHERE id = ?", array($satkerid));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
}