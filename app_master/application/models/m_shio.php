<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class m_shio extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function get_shio()
	{
		$this->load->database();
		$q = $this->db->query("	
			SELECT
				id ,
				namashio as text ,
				unsurshio as unsur ,
				concat(namashio, ' / ', unsurshio) as desc
			FROM shio 
			ORDER BY concat(namashio, ' / ', unsurshio)");
		$this->db->close();
		return $q->result_array();
	}
}
