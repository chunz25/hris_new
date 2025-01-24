<?php 
class tp_notifikasi{
	private $CI; 
	function __construct(){
		$this->CI =& get_instance();
    }
	function tambahNotif2($params){
		$this->CI->load->database();		
		$this->CI->db->trans_start();
		$q = $this->CI->db->query("
			INSERT INTO notifikasi(tglnotif,jenisnotif,description,penerima,useridfrom,usergroupidfrom,pengirim,isshow,modulid,modul,isread)
			VALUES(NOW(),?,?,?,CAST(? AS INT),CAST(? AS INT),?,'1',CAST(? AS INT),?,0);		
		", $params);
		$this->CI->db->trans_complete();		
		$this->CI->db->close();
		return $this->CI->db->trans_status();		
	}
	function getShortNotif($penerimaid){
		$this->CI->load->database();
		$q = $this->CI->db->query("
			SELECT TOP 5 n.notifid, CONVERT(CHAR(19),n.tglnotif,120) tglnotif,
			n.jenisnotif, p.Nama, P.NIPBaru
			FROM dbo.notifikasi n
			LEFT JOIN dbo.Pegawai p ON n.pengirim = p.PegawaiID
			WHERE n.penerima = ?
			ORDER BY n.tglnotif DESC				
		", array($penerimaid));
		$this->CI->db->close();
		return $q->result_array();
	}
}