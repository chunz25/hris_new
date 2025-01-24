<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class tp_menu{
	function akses_fitur(){
		$CI =& get_instance();
		$CI->load->database();								
		$logged_in = $CI->session->userdata('log_in');
		if(! isset($logged_in) or $logged_in != 1){
			$usergroupid = $CI->config->item('usergroup_default');
		}
		else{
			$usergroupid = $CI->session->userdata('aksesid_'.$CI->config->item('modul_name'));
		}
		$modulid = $CI->config->item('modulid');		
		$res=$CI->db->query("
			SELECT UM.MENUID,UM.PARENTID,UM.MENU, UM.ICONS,UM.LINK,UM.VISIBLE,UM.NOURUT,
			  CASE WHEN UH.USERGROUPID=? THEN 1 ELSE 0 END HAS 
			FROM usermng_fitur UF 
			INNER JOIN usermng_hakakses UH ON UF.FITURID=UH.FITURID AND UH.USERGROUPID=?
			LEFT JOIN usermng_menu UM ON UF.MENUID=UM.MENUID
			WHERE UM.MODULID=? AND UF.ISMENU=1 AND UM.VISIBLE=1 ORDER BY NOURUT ASC 		
		", array($usergroupid, $usergroupid, $modulid));
		$render = '<ul class="nav navbar-nav navbar-right header">';
		foreach($res->result() as $r){			
			$render .= '<li><a href="'.site_url().'/'.$r->LINK.'">'.$r->MENU.'</a></li>';
		}
		$render .= '</ul>';
		return $render;		
	}	
	function akses_fitur2(){
		$CI =& get_instance();
		$CI->load->database();
		$res=$CI->db->query("
			SELECT * FROM acl 
			WHERE acl_level='0' AND acl_parent='' AND visible='1' 
			AND usergroup_id LIKE '%%' 
			AND LOWER(module)=LOWER('ecommerce') 
			ORDER BY acl_order 				
		");
		
		$render = '<ul class="nav navbar-nav navbar-right header">';
		foreach($res->result() as $r){			
			$render .= '<li><a href="'.site_url().'/'.$r->acl_link.'">'.$r->acl_name.'</a></li>';
		}
		$render .= '</ul>';
		return $render;
	}	
}