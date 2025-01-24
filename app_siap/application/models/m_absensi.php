<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class m_absensi extends CI_Model {
	function __construct(){
		parent::__construct();
	}	

	function getListPegawai($params){
		$this->load->database();
		
		$cond_where = '';

		$order_by = "
			ORDER BY vj.lokasikerja ASC,
			case 
				when vj.levelid = '13' and p.nik = '1100003' then 1 
				when vj.levelid = '13' and p.nik = '20050035' then 2 
				when vj.levelid = '13' and p.nik = '20080157' then 3 
				when vj.levelid = '13' and p.nik = '20080158' then 4
				when vj.levelid = '13' and p.nik = '20080159' then 5 
				when vj.levelid = '13' and p.nik = '18000174' then 6 
				when vj.levelid = '10' then 7 
				when vj.levelid = '11' then 8 
			else 9 end, 
			l.gol ASC,
       		COALESCE(vj.satkerid,'99') ASC
       	";

		if( !empty($params['v_satkerid']) && !empty($params['v_lokasiid']))
		{
			$cond_where = " AND vj.satkerid LIKE '".$params['v_satkerid']."' || '%' AND vj.lokasikerja = '".$params['v_lokasiid']."' "; 
		} 
		else if( !empty($params['v_satkerid']) ){
			$cond_where = " AND vj.satkerid LIKE '".$params['v_satkerid']."' || '%' "; 
		}
		else if( !empty($params['v_lokasiid']) ){
			$cond_where = " AND vj.lokasikerja = '".$params['v_lokasiid']."' "; 
		}
		
		$query = "
			SELECT p.pegawaiid, fnnamalengkap(p.namadepan, p.namabelakang) nama, p.nik, vj.satkerid, 
				public.fnsatkerlevel(vj.satkerid,'1') AS direktorat,
				public.fnsatkerlevel(vj.satkerid,'2') AS divisi,
				public.fnsatkerlevel(vj.satkerid,'3') AS departemen,
				public.fnsatkerlevel(vj.satkerid,'4') AS seksi,
				public.fnsatkerlevel(vj.satkerid,'5') AS subseksi,
				vj.jabatanid, j.jabatan, vj.levelid, l.level, l.gol,
				p.emailkantor, p.telp, vj.lokasikerja lokasiid, loc.lokasi, sp.statuspegawai,
				TO_CHAR(vj.tglmulai, 'DD/MM/YYYY') tglmulai, TO_CHAR(vj.tglakhirkontrak, 'DD/MM/YYYY') tglakhirkontrak, f.fingerid
			FROM pegawai p
			LEFT JOIN vwjabatanterakhir vj ON p.pegawaiid = vj.pegawaiid
			LEFT JOIN satker s ON vj.satkerid = s.satkerid
			LEFT JOIN jabatan j ON vj.jabatanid = j.jabatanid
			LEFT JOIN level l ON vj.levelid = l.levelid
			LEFT JOIN statuspegawai sp ON vj.statuspegawaiid = sp.statuspegawaiid
			LEFT JOIN lokasi loc ON vj.lokasikerja = loc.lokasiid	
			LEFT JOIN public.fingerprint f ON p.nik = f.nik	
			WHERE 1=1 AND vj.keteranganpegawai = '1' AND f.fingerid is not null
		" . $cond_where .$order_by;
		
		$q = $this->db->query("
			SELECT a.*
			FROM (".$query.") a
			OFFSET ".$params['v_start']." LIMIT ".$params['v_limit']."
		", $params);
		
		$q2 = $this->db->query("
			SELECT COUNT(*) as jml
			FROM (".$query.") a
		");
		
		$this->db->close();
		$result = array('success' => true, 'count' => $q2->first_row()->jml, 'data' => $q->result_array());
		return $result;
	}	

	function getListAbsensi($params) {
		$this->db = $this->load->database('hrd', TRUE);

		$query = "
		WITH act1 AS (
			SELECT b.USERID AS id, b.BADGENUMBER, b.NAME, MONTH(a.CHECKTIME) EMonth,
			CONVERT(VARCHAR(10),a.CHECKTIME, 103) EDate,
			CONVERT(VARCHAR(10),MIN(a.CHECKTIME),108) masuk,
			CASE WHEN CONVERT(VARCHAR(10), MIN(a.CHECKTIME),108) >= '08:31:00' THEN
			CONVERT(VARCHAR(5), DATEADD(SECOND,DATEDIFF(SECOND,CONVERT(VARCHAR(11),a.CHECKTIME, 120) + ' 08:30',CONVERT(VARCHAR(16),MIN(a.CHECKTIME),120)),0),108) ELSE NULL 
			END keterlambatan,
			CONVERT(VARCHAR(10),MAX(a.CHECKTIME),108) pulang,
			CASE WHEN CONVERT(VARCHAR(10),MAX(a.CHECKTIME),108) < '17:30:00' THEN
			CONVERT (VARCHAR(5),DATEADD(SECOND,DATEDIFF(SECOND,CONVERT(VARCHAR(16),MAX(a.CHECKTIME),120),CONVERT(VARCHAR(11),a.CHECKTIME, 120) + ' 17:30'),0),108) ELSE NULL 
			END pulangcepat,
			CONVERT (VARCHAR(5),DATEADD(SECOND,DATEDIFF(SECOND,CONVERT(VARCHAR(16),MIN(a.CHECKTIME),120),CONVERT(VARCHAR(16),MAX(a.CHECKTIME),120)),0 ),108 ) jmljam,
			CASE 
				WHEN CONVERT(VARCHAR(10),MIN(a.CHECKTIME),108) >= '15:00:00' THEN 'S1'
				WHEN CONVERT(VARCHAR(10),MIN(a.CHECKTIME),108) >= '12:00:00' THEN 'M1'
				WHEN CONVERT(VARCHAR(10),MIN(a.CHECKTIME),108) >= '08:00:00' THEN 'P1'
			ELSE NULL END jadwal
		FROM
			CHECKINOUT a
			LEFT JOIN USERINFO b ON a.USERID = b.USERID 
		WHERE
			a.CHECKTIME BETWEEN '".$params['v_tglmulai']." 00:00:00.000' AND '".$params['v_tglselesai']." 23:59:59.000' 
			AND b.USERID IN('".$params['v_fingerid']."')
		GROUP BY
			b.USERID, b.BADGENUMBER, b.NAME, MONTH(a.CHECKTIME),
			CONVERT(VARCHAR(11),a.CHECKTIME, 120),
			CONVERT(VARCHAR(10),a.CHECKTIME, 103) 
		) 
		SELECT * FROM (
		SELECT 
			ROW_NUMBER() OVER (ORDER BY d.id, SUBSTRING(CONVERT(VARCHAR(10),d.CalendarDate, 120), 4, 2), SUBSTRING(CONVERT(VARCHAR(10),d.CalendarDate, 120), 1, 2) ) AS rownum, d.CalendarDate, e.EDate, CASE WHEN e.EDate IS NULL THEN '1' ELSE NULL END absent, d.NAME, d.id, d.BADGENUMBER, e.masuk,
			'08:30' AS jammasuk, '17:30' AS jamkeluar, e.keterlambatan, e.pulang, e.pulangcepat, e.jmljam, CASE WHEN d.WeekdayFlag = 'Weekend' THEN '1' ELSE NULL END weekdayflag, e.jadwal
		FROM
		(
			SELECT DISTINCT 
			CONVERT(VARCHAR(10),CalendarDate, 103) CalendarDate,
			WeekdayFlag,
			c.USERID AS id, 
			c.NAME,
			c.BADGENUMBER,
			CONVERT(VARCHAR(10),CalendarDate, 103) + CAST(c.USERID AS varchar) 'unik' 
			FROM CalendarDate
			CROSS JOIN( SELECT USERID, NAME, BADGENUMBER FROM USERINFO ) c 
			WHERE CalendarDate BETWEEN '".$params['v_tglmulai']."' AND '".$params['v_tglselesai']."' 
			AND c.USERID IN('".$params['v_fingerid']."') 
		) d
		LEFT JOIN( SELECT DISTINCT id, EDate, jadwal, EMonth, NAME, BADGENUMBER, masuk, keterlambatan, pulang, pulangcepat, jmljam, (EDate + CAST(id AS varchar)) 'unik1' FROM act1 ) e ON d.unik = e.unik1
		) A
		WHERE rownum >= '".$params['v_start']."' and rownum <  '".$params['v_start']."' + ".$params['v_limit']."
		";

		$q = $this->db->query( $query );

		$q2 = $this->db->query("
			SELECT COUNT(CalendarDate) * '".$params['v_finger']."' AS jml 
			FROM CalendarDate 
			WHERE CalendarDate BETWEEN '".$params['v_tglmulai']."' AND '".$params['v_tglselesai']."'
		");

		$result = array('success' => true, 'count' => $q2->first_row()->jml, 'data' => $q->result_array());
		return $result;
	}

	function getListAbsensiCetak($params) {
		$this->db = $this->load->database('hrd', TRUE);

		$query = "
		WITH act1 AS (
			SELECT b.USERID AS id, b.BADGENUMBER, b.NAME, MONTH(a.CHECKTIME) EMonth,
			CONVERT(VARCHAR(10),a.CHECKTIME, 103) EDate,
			CONVERT(VARCHAR(10),MIN(a.CHECKTIME),108) masuk,
			CASE WHEN CONVERT(VARCHAR(10), MIN(a.CHECKTIME),108) > '08:30:00' THEN
			CONVERT(VARCHAR(5), DATEADD(SECOND,DATEDIFF(SECOND,CONVERT(VARCHAR(11),a.CHECKTIME, 120) + ' 08:30',CONVERT(VARCHAR(16),MIN(a.CHECKTIME),120)),0),108) ELSE NULL 
			END keterlambatan,
			CONVERT(VARCHAR(10),MAX(a.CHECKTIME),108) pulang,
			CASE WHEN CONVERT(VARCHAR(10),MAX(a.CHECKTIME),108) < '17:30:00' THEN
			CONVERT (VARCHAR(5),DATEADD(SECOND,DATEDIFF(SECOND,CONVERT(VARCHAR(16),MAX(a.CHECKTIME),120),CONVERT(VARCHAR(11),a.CHECKTIME, 120) + ' 17:30'),0),108) ELSE NULL 
			END pulangcepat,
			CONVERT (VARCHAR(5),DATEADD(SECOND,DATEDIFF(SECOND,CONVERT(VARCHAR(16),MIN(a.CHECKTIME),120),CONVERT(VARCHAR(16),MAX(a.CHECKTIME),120)),0 ),108 ) jmljam,
			CASE 
				WHEN CONVERT(VARCHAR(10),MIN(a.CHECKTIME),108) >= '15:00:00' THEN 'S1'
				WHEN CONVERT(VARCHAR(10),MIN(a.CHECKTIME),108) >= '12:00:00' THEN 'M1'
				WHEN CONVERT(VARCHAR(10),MIN(a.CHECKTIME),108) >= '08:00:00' THEN 'P1'
			ELSE NULL END jadwal
		FROM
			CHECKINOUT a
			LEFT JOIN USERINFO b ON a.USERID = b.USERID 
		WHERE
			a.CHECKTIME BETWEEN '".$params['v_tglmulai']." 00:00:00.000' AND '".$params['v_tglselesai']." 23:59:59.000' 
			AND b.USERID IN('".$params['v_fingerid']."')
		GROUP BY
			b.USERID, b.BADGENUMBER, b.NAME, MONTH(a.CHECKTIME),
			CONVERT(VARCHAR(11),a.CHECKTIME, 120),
			CONVERT(VARCHAR(10),a.CHECKTIME, 103) 
		) 
		SELECT * FROM (
		SELECT 
			ROW_NUMBER() OVER (ORDER BY d.id, SUBSTRING(CONVERT(VARCHAR(10),d.CalendarDate, 120), 4, 2), SUBSTRING(CONVERT(VARCHAR(10),d.CalendarDate, 120), 1, 2) ) AS rownum, d.CalendarDate, e.EDate, CASE WHEN e.EDate IS NULL THEN 'Absent' ELSE NULL END absent, d.NAME, d.id, d.BADGENUMBER, e.masuk,
			'08:30' AS jammasuk, '17:30' AS jamkeluar, e.keterlambatan, e.pulang, e.pulangcepat, e.jmljam, CASE WHEN d.WeekdayFlag = 'Weekday' THEN NULL ELSE d.WeekdayFlag END weekdayflag, e.jadwal
		FROM
		(
			SELECT DISTINCT 
			CONVERT(VARCHAR(10),CalendarDate, 103) CalendarDate,
			WeekdayFlag,
			c.USERID AS id, 
			c.NAME,
			c.BADGENUMBER,
			CONVERT(VARCHAR(10),CalendarDate, 103) + CAST(c.USERID AS varchar) 'unik' 
			FROM CalendarDate
			CROSS JOIN( SELECT USERID, NAME, BADGENUMBER FROM USERINFO ) c 
			WHERE CalendarDate BETWEEN '".$params['v_tglmulai']."' AND '".$params['v_tglselesai']."' 
			AND c.USERID IN('".$params['v_fingerid']."')
		) d
		LEFT JOIN( SELECT DISTINCT id, EDate, jadwal, EMonth, NAME, BADGENUMBER, masuk, keterlambatan, pulang, pulangcepat, jmljam, (EDate + CAST(id AS varchar)) 'unik1' FROM act1 ) e ON d.unik = e.unik1
		) A
		WHERE rownum >= '0' and rownum <  '100000'
		";

		$q = $this->db->query( $query );

		$result = array('success' => true, 'data' => $q->result_array());
		return $result;
	}

	function getListSchedule($params){
		$this->db = $this->load->database('default', TRUE);
		$query = "
			SELECT * FROM (
				SELECT
				   unnest(array[
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-01'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-02'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-03'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-04'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-05'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-06'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-07'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-08'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-09'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-10'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-11'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-12'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-13'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-14'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-15'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-16'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-17'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-18'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-19'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-20'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-21'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-22'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-23'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-24'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-25'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-26'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-27'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-28'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-29'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-30'),
					 CONCAT(to_char(tgl , 'YYYY-MM'),'-31')]
					 ) AS day,
				   unnest(array[
					 tgl1, tgl2, tgl3,tgl4, tgl5, tgl6, tgl7, tgl8, tgl9, tgl10,
					 tgl11, tgl12, tgl13,tgl14, tgl15, tgl16, tgl17, tgl18, tgl19, tgl20,
					 tgl21, tgl22, tgl23,tgl24, tgl25, tgl26, tgl27, tgl28, tgl29, tgl30, tgl31
					 ]) AS value
				FROM absensi.schedule a
				LEFT JOIN public.fingerprint b ON a.nik = CAST(b.nik AS INT) 
				WHERE b.fingerid IN ('".$params['v_fingerid']."')
			) A
			WHERE day BETWEEN '".$params['v_tglmulai']."' AND '".$params['v_tglselesai']."'
			ORDER BY day ASC
		";
		
		$q = $this->db->query("
			SELECT a.*
			FROM (".$query.") a
		", $params);

		$q2 = $this->db->query("
			SELECT COUNT(*) as jml
			FROM (".$query.") b
		");
		
		$this->db->close();
		$result = array('success' => true, 'count' => $q2->first_row()->jml, 'data' => $q->result_array());
		return $result;
	}	

	function addPengajuanSchedule($params){
		$this->load->database();		
		$this->db->trans_start();
		$this->db->query("
			DELETE FROM absensi.schedule WHERE nik = ? and tgl = ?", array($params['nik'],$params['tgl'])
		);
		$this->db->query("
			SELECT absensi.sp_addschedule(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
		", $params);
		$this->db->trans_complete();	
		$this->db->close();
		return $this->db->trans_status();		
	}	
	function updateSchedule($params){
		$this->load->database();		
		$this->db->trans_start();
		$this->db->query("
			SELECT absensi.sp_updschedule(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
		", $params);
		$this->db->trans_complete();	
		$this->db->close();
		return $this->db->trans_status();		
	}	
	function getSchedule($sendparams){
		$this->load->database();
		$q = $this->db->query("
			SELECT id FROM absensi.schedule WHERE nik = '".$sendparams['v_nik']."' and tgl = '".$sendparams['v_tgl']."'
		", $sendparams);
		$this->db->close();
		return $q->result_array();
	}	
}