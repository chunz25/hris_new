<?php 
class tp_portalnotification{
	var $CI; 
	function __construct(){
		$this->CI =& get_instance();
    }
	function addNotif($params){
		$this->CI->load->database();		
		$this->CI->db->trans_start();
		$q = $this->CI->db->query("
			INSERT INTO kehadiran.cuti_notifikasi(tglnotif,jenisnotif,description,penerima,useridfrom,usergroupidfrom,pengirim,isshow,modulid,modul,isread)
			VALUES(NOW(),?,?,?,CAST(? AS INT),CAST(? AS INT),?,'1',CAST(? AS INT),?,0);		
		", $params);
		$this->CI->db->trans_complete();		
		$this->CI->db->close();
		return $this->CI->db->trans_status();		
	}
	function getCountNotif($penerimaid){
	  $this->CI->db->where('penerima',$penerimaid);
      return $this->CI->db->count_all_results("notifikasi");
    }
	function getShortNotif($params){
		$this->CI->load->database();
		$q = $this->CI->db->query("
		SELECT sp.label AS labelid,
			CASE
				WHEN sp.label = '2' THEN 'Modul Cuti'
				WHEN sp.label = '4' THEN 'Modul Policies'
				WHEN sp.label = '5' THEN 'Modul Contract'
				WHEN sp.label = '10' THEN 'Modul Absensi'
				WHEN sp.label = '98' THEN 'Modul Daily Report'
			ELSE NULL END AS label,
		(COUNT(n.notifid)+COUNT(cn.notifid)+COUNT(tn.notifid)) jml
		FROM (
			SELECT '2' AS label
			UNION ALL
			SELECT '4' AS label
			UNION ALL
			SELECT '5' AS label
			UNION ALL
			SELECT '10' AS label
			UNION ALL
			SELECT '98' AS label
		) sp
		LEFT JOIN kehadiran.cuti_notifikasi n ON CAST(sp.label AS INT) = n.modulid
		LEFT JOIN kehadiran.absensi_notifikasi cn ON CAST(sp.label AS INT) = cn.modulid
		WHERE
			(n.penerima = '" . $params['v_penerimaid'] . "' and (n.isread IS NULL OR n.isread = 0) and n.modulid not in ('3')) OR
			(cn.penerima = '" . $params['v_penerimaid'] . "' and (cn.isread IS NULL OR cn.isread = 0))
		GROUP BY sp.label
		ORDER BY sp.label ASC
		", array($params));
		$this->CI->db->close();
		return $q->result_array();
	}
	function getShortNotifHR($params){
		$this->CI->load->database();
		$q = $this->CI->db->query("
		SELECT sp.label AS labelid, 
		CASE 
			WHEN sp.label = '2' THEN 'Modul Cuti' 	
			WHEN sp.label = '4' THEN 'Modul Policies'
			WHEN sp.label = '5' THEN 'Modul Contract'
			WHEN sp.label = '10' THEN 'Modul Absensi'
		ELSE NULL END AS label,
		(COUNT(n.notifid)+COUNT(na.notifid)) jml
		FROM (
			SELECT '2' AS label
			UNION ALL
			SELECT '4' AS label
			UNION ALL
			SELECT '5' AS label
			UNION ALL 
			SELECT '10' AS label
		) sp
		LEFT JOIN kehadiran.cuti_notifikasi n ON CAST(sp.label AS INT) = n.modulid
		LEFT JOIN kehadiran.absensi_notifikasi na ON CAST(sp.label AS INT) = na.modulid
		WHERE 
			(n.penerima = '".$params['v_penerimaid']."' and (n.isread IS NULL OR n.isread = 0) and n.modulid not in ('3')) OR
			(na.penerima = '".$params['v_penerimaid']."' and (na.isread IS NULL OR na.isread = 0))
		GROUP BY sp.label
		ORDER BY sp.label ASC
		", array($params));
		$this->CI->db->close();
		return $q->result_array();
	}
	function getCountNotifUnread($params) {
		$this->CI->load->database();
		$q = $this->CI->db->query("
		SELECT( 
			(SELECT COUNT(*) AS INT FROM kehadiran.cuti_notifikasi WHERE penerima = '".$params['v_penerimaid']."' AND (isread IS NULL OR isread = 0) AND modulid not in ('3')) 
			+
			(SELECT COUNT(*) AS INT FROM kehadiran.absensi_notifikasi WHERE penerima = '".$params['v_penerimaid']."' AND (isread IS NULL OR isread = 0))
		) AS jml
		", array($params));
		$this->CI->db->close();
		return $q->first_row()->jml;		
	}
}