<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class m_jeniscuti extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function getJenisCuti()
	{
		$this->load->database();
		$q = $this->db->query("select id, name as text from kehadiran.cuti_jenis_hdr where id not in('6') order by id");
		return $q->result_array();
	}
	function getDetailJenisCuti($jeniscutiid)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT id, name AS text, jatahcuti, 'HARI KERJA' as satuan 
			FROM kehadiran.cuti_jenis_dtl 
			WHERE hdrid = ?
			ORDER BY id		
		", array($jeniscutiid));
		return $q->result_array();
	}
}