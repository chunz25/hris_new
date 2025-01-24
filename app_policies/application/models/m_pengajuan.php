<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class m_pengajuan extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	
	function getComboNamaDokumen() {
		$this->load->database();		
		$sql = "
			SELECT id, nama AS text FROM public.policies_hdr
			ORDER BY id asc
		";
		
		$q = $this->db->query($sql);
		$this->db->close();
		return $q->result_array();
	}
	
	function getComboDivisi() {
		$this->load->database();		
		$sql = "
		SELECT  
			a.code as id ,
			CONCAT(b.code,'_',a.direktorat) as text 
		FROM struktur.vw_satkertree a
			LEFT JOIN struktur.vw_satkertree b ON b.code = LEFT(a.code,3)
		WHERE LENGTH(a.code) = '5'
		ORDER BY a.id, a.code asc
		";
		
		$q = $this->db->query($sql);
		$this->db->close();
		return $q->result_array();
	}
	
	function getComboDept($params) {
		$this->load->database();		
		$q = $this->db->query("
		SELECT 
			a.code AS id, 
			a.divisi AS text 
		FROM struktur.vw_satkertree a
			LEFT JOIN struktur.vw_satkertree b on b.code = LEFT(a.code,3)
		WHERE a.code LIKE '".$params['v_dept']."' || '%' 
			AND LENGTH(a.code) = '7'
		ORDER BY b.id, a.code
		", array($params));
		$this->db->close();
		return $q->result_array();
	}
	
	function addPolicies($params) {
		$mresult = $this->tp_connpgsql->callSpReturn('public.sp_addpolicies', $params);
		return $mresult['firstrow'];		
	}
		
	function updPolicies($params){
		$this->load->database();		
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_updpolicies(?,?,?,?,?,?,?,?,?,?)
		", $params);
		$this->db->trans_complete();		
		$this->db->close();
		return $this->db->trans_status();		
	}	
}