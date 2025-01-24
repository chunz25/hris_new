<?php
class App_Controller extends CI_Controller{
	public function App_Controller () {
		parent::__construct ();		
	}					
	public function app_check(){
		$this->sesscheck();
	}
	public function sesscheck() {
		if($this->session->userdata('log_in') != 1){
			redirect(config_item('url_index').'/login');
		}		
	}
	function app_menu_access($usergroupid, $modulid){
		$this->load->database();
		$q = $this->db->query("
			SELECT um.menuid, um.parentid, um.menu, um.icons, um.link, um.visible, um.nourut, 1 AS has
			FROM usermngfitur uf
			LEFT JOIN usermnghakakses uh ON uf.fiturid = uh.fiturid AND uh.usergroupid = 1
			LEFT JOIN usermngmenu um ON uf.menuid = um.menuid
			WHERE um.modulid = 1 AND uf.ismenu = 1 AND um.visible = '1' AND uh.usergroupid = 1
			ORDER BY um.nourut ASC		
		", array($usergroupid, $modulid, $usergroupid));
		$this->db->close();
		$hak = array();
		foreach($q->result() as $row){
			$row->has = ($row->has)? TRUE : FALSE;
			$hak[]=$row;
		}
		return $hak;
	}		
	function app_fitur_access($usergroupid, $modulid){
		$this->load->database();
		$q = $this->db->query("
			SELECT uf.fiturid, uf.fitur, uf.datafitur, um.menuid, um.menu, uh.usergroupid,
				CASE WHEN uh.usergroupid = 1 THEN 1 ELSE 0 END has
			FROM usermngfitur uf
			LEFT JOIN usermnghakakses uh ON uf.fiturid = uh.fiturid AND uh.usergroupid = 1
			LEFT JOIN usermngmenu um ON uf.menuid = um.menuid
			WHERE um.modulid = 1		
		", array($usergroupid, $usergroupid, $modulid));
		$this->db->close();
		$hak=array();
		foreach($q->result() as $row){
			$hak[$row->fiturid] = ($row->has) ? TRUE : FALSE;
			$hak[$row->fiturid.'_data'] = $row->datafitur;
		}
		return $hak;		
	}		
}
?>