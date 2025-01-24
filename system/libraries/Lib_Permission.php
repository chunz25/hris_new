<?php
class Lib_Permission{
	function access($usergroupid,$fiturid,$act=true){
		$CI =& get_instance();
		$CI->load->database();
		$num = array();
		$exp_fiturid = explode(',',$fiturid);
		$i = 0;
		foreach($exp_fiturid as $f){
			$q = $CI->db->query("SELECT * FROM MASTER_HAKAKSES WHERE usergroup_id=? AND fitur_id=?",array($usergroupid,$f));
			if($q->num_rows()>0){
				$i += 1;
			}
		}		
		if($i > 0){
			return true; 
		} 
		else{
			if($act){
				echo json_encode(array('success'=>false,'message'=>'Anda Tidak Memiliki Hak Akses.'));
				exit;
			}
			return false;
		}
	}
	function fitur_access(){
		$CI =& get_instance();
		$CI->load->database();
		$q = $CI->db->query("
			SELECT mu.usergroup_id, mu.usergroup, mf.fitur_id AS fiturid, mm.menu, 
				CASE WHEN mh.fitur_id IS NOT NULL THEN 1 ELSE 0 END AS has 
			FROM (master_usergroup mu CROSS JOIN master_fitur mf) 
			LEFT JOIN master_hakakses mh ON mf.fitur_id = mh.fitur_id AND mu.usergroup_id = mh.usergroup_id 
			LEFT JOIN master_menu mm ON mf.menu_id = mm.menu_id 
			WHERE mU.usergroup_id = ? ORDER BY mu.usergroup_id, mf.fitur_id		
		");
		$CI->db->close();
	}
}