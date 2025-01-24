<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class m_history extends CI_Model {
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
	
	function getListPolicies($params){
		$mresult = $this->tp_connpgsql->callSpCount('public.sp_getlistpolicies', $params, false);
		return $mresult;
	}

	function deleteDraft($params) {
		$this->load->database();		
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_deletepolicies(?);		
		", $params);
		$this->db->trans_complete();		
		$this->db->close();
		return $this->db->trans_status();
	}

	function getPoliciesById($params) {
		$mresult = $this->tp_connpgsql->callSpReturn('public.sp_getpoliciesbyid', $params);
		return $mresult['firstrow'];				
	}

	function getInfoPegawai($params) {
		$mresult = $this->tp_connpgsql->callSpReturn('public.sp_getinfopegawai', $params);
		return $mresult;		
	}	
}