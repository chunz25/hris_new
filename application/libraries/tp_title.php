<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class tp_title{
	var $title;
	var $data;	
	public function dina_title(){
		$result = $this->dina();
		return $this->title = $result['title'];
	}
	public function dina_description(){
		$result = $this->dina();
		return $this->title = $result['description'];
	}
	public function dina_keyword(){
		$result = $this->dina();
		return $this->title = $result['keyword'];		
	}
	private function dina(){
		/*$CI =& get_instance();
		$CI->load->database();				
		if($CI->uri->total_segments() > 1){
			$link = $CI->uri->segment(3);
			$q = $CI->db->query("
				SELECT nama_produk, deskripsi1 deskripsi, seo FROM produk WHERE id_produk = ?
			", array($link));
			if($q->num_rows() > 0){
				$this->data = array('title'=>$q->first_row()->nama_produk, 'description'=>$q->first_row()->deskripsi, 'keyword'=>$q->first_row()->seo);
			}
			else{
				$this->data = array('title'=>'nusastyle.com', 'description'=>'nusastyle.com', 'keyword'=>'nusastyle.com');
			}
		}
		else{
			$logged_in = $CI->session->userdata('log_in');
			if(! isset($logged_in) or $logged_in != 1){
				$usergroupid = $CI->config->item('usergroup_default');
			}
			else{
				$usergroupid = $CI->session->userdata('aksesid_'.$CI->config->item('modul_name'));					
			}
			$modulid = $CI->config->item('modulid');		
			$q=$CI->db->query("
				SELECT UM.MENUID,UM.PARENTID,UM.MENU, UM.ICONS,UM.LINK,UM.VISIBLE,UM.NOURUT,
				  CASE WHEN UH.USERGROUPID=? THEN 1 ELSE 0 END HAS, UM.META_DESCRIPTION, UM.META_KEYWORD
				FROM usermng_fitur UF 
				INNER JOIN usermng_hakakses UH ON UF.FITURID=UH.FITURID AND UH.USERGROUPID=?
				LEFT JOIN usermng_menu UM ON UF.MENUID=UM.MENUID
				WHERE UM.MODULID=? AND UF.ISMENU=1 AND UM.VISIBLE=1 AND UPPER(UM.LINK) = UPPER(?) ORDER BY NOURUT ASC 		
			", array($usergroupid, $usergroupid, $modulid, $CI->uri->segment(1)));
			$this->data = array('title'=>$q->first_row()->MENU, 'description'=>$q->first_row()->META_DESCRIPTION, 'keyword'=>$q->first_row()->META_KEYWORD);
		}		
		return $this->data;
		*/
	}	
}