<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class m_goldarah extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function get_goldarah()
	{
		$this->load->database();
		$q = $this->db->query("	
			SELECT 
				id , 
				name as text ,
				rhesus ,
				CONCAT(name,CASE WHEN RIGHT(rhesus,1) = 'A' THEN '' ELSE RIGHT(rhesus,1) END) as desc
			FROM 
				goldarah 
			ORDER BY 
				CONCAT(name,CASE WHEN RIGHT(rhesus,1) = 'A' THEN '' ELSE RIGHT(rhesus,1) END)");
		$this->db->close();
		return $q->result_array();
	}
}
