<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class m_report extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	/* Report by Divisi */
	function statistikDivisi($satkerid)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT
				vs.code as satkerid ,
				vs.name as satker ,
				count(sp.pegawaiid) as jml
			FROM
				struktur.vw_satkertree vs
				LEFT JOIN struktur.vw_satkertree vs2 ON vs2.code LIKE vs.code || '%'
				LEFT JOIN struktur.satkerpegawai sp ON sp.satkerid = vs2.id
				LEFT JOIN riwayatjabatan rj ON rj.pegawaiid = sp.pegawaiid
			WHERE
				rj.tglselesai IS NULL
				and   vs.code like ? || '%' 
				and (length(vs.code) = length(?) + 2 OR length(vs.code) = length(?) + 3) 
				and vs.code <> ?
			GROUP BY
				vs.code ,
				vs.name
			ORDER BY vs.code
		", array($satkerid, $satkerid, $satkerid, $satkerid));
		$this->db->close();
		$result = array('success' => true, 'data' => $q->result_array());
		return $result;
	}

	function reportListDivisi($params)
	{
		$this->load->database();
		$query = "
			SELECT 
				a.id as pegawaiid ,
				a1.fullname AS nama ,
				u.nik ,
				b1.id as idsatker ,
				b1.code as satkerid ,
				b1.unitkerja ,
				b1.direktorat ,
				b1.divisi ,
				b1.departemen ,
				b1.seksi ,
				b1.subseksi ,
				c1.id as jabatanid,
				c1.name as jabatan ,
				c2.id as levelid ,
				c2.name as level,
				c2.gol ,
				c2.susunan as idnew ,
				dp.emailkantor ,
				dp.notelp as telp ,
				c3.name as lokasi ,
				c.tglmulai
			FROM 
				pegawai a
				LEFT JOIN namapegawai a1 ON a1.pegawaiid = a.id
				LEFT JOIN users u ON u.id = a.userid
				LEFT JOIN datapegawai dp ON dp.pegawaiid = a.id
				LEFT JOIN struktur.satkerpegawai b ON b.pegawaiid = a.id
				LEFT JOIN struktur.vw_satkertree_hr b1 ON b1.id = b.satkerid
				LEFT JOIN riwayatjabatan c ON c.pegawaiid = a.id
				LEFT JOIN struktur.jabatan c1 ON c1.id = b.jabatanid
				LEFT JOIN struktur.levelgrade c2 ON c2.id = b.levelid
				LEFT JOIN lokasi c3 ON c3.id = c.lokasiid
			WHERE 
				c.tglselesai IS NULL
				AND b1.code like '" . $params['v_satkerid'] . "' || '%'
			ORDER BY
				c2.susunan
		";

		$q = $this->db->query("
			SELECT a.*
			FROM (" . $query . ") a
			OFFSET " . $params['v_start'] . " LIMIT " . $params['v_limit'] . "
		", $params);

		$q2 = $this->db->query("
			SELECT COUNT(*) as jml
			FROM (" . $query . ") a
		");

		$this->db->close();
		$result = array('success' => true, 'count' => $q2->first_row()->jml, 'data' => $q->result_array());
		return $result;
	}
	/* Report by Divisi */

	/* Report By Status Pegawai */
	function statistikStatusPegawai($satkerid)
	{
		$this->load->database();
		$q = $this->db->query("
			with ctepegawai as ( 
				select 
					a.pegawaiid ,
					vs.code as satkerid ,
					a.statuspegawaiid
				from 
					riwayatjabatan a	
					left join struktur.satkerpegawai b on b.pegawaiid = a.pegawaiid
					left join struktur.vw_satkertree vs ON vs.id = b.satkerid
				where 
					a.tglselesai is null )
					
				select
					a.id as labelid ,
					a.name as label ,
					count(b.pegawaiid) as jml
				from statuspegawai a
				left join ctepegawai b on b.statuspegawaiid = a.id 
				where a.id in ('7','2','1')
				and b.satkerid like ? || '%'
				group by 
					a.id
				ORDER BY 
					CASE 
						WHEN a.id = 7 THEN 1
						WHEN a.id = 1 THEN 3
					ELSE a.id
					END
		", array($satkerid));
		$this->db->close();
		$result = array('success' => true, 'data' => $q->result_array());
		return $result;
	}

	function reportListStatusPegawai($params)
	{
		$where = '';
		if (!empty($params['v_statuspegawaiid']) || $params['v_statuspegawaiid'] !== null ) {
			$where = "AND c.statuspegawaiid = COALESCE(CAST('" . $params['v_statuspegawaiid'] . "' AS INT), c.statuspegawaiid)";
		}
		$this->load->database();

		$query = "
			 SELECT 
				a.id AS pegawaiid ,
				a1.fullname AS nama ,
				u.nik ,
				b.satkerid AS idsatker ,
				b1.code AS satkerid ,
				b1.unitkerja ,
				b1.direktorat ,
				b1.divisi ,
				b1.departemen ,
				b1.seksi ,
				b1.subseksi ,
				b.jabatanid ,
				c1.name AS jabatan ,
				b.levelid ,
				c2.name AS level ,
				c2.gol ,
				c2.susunan AS idnew ,
				c.statuspegawaiid ,
				c4.name AS statuspegawai ,
				dp.jeniskelamin ,
				dp.emailpribadi as email ,
				dp.emailkantor ,
				dp.notelp AS telp ,
				c.tglmulai ,
				c.tglselesai ,
				c.keterangan ,
				c3.name AS lokasi ,
				c.tglakhirkontrak
			FROM 
				pegawai a
				LEFT JOIN namapegawai a1 ON a1.pegawaiid = a.id
				LEFT JOIN users u ON u.id = a.userid
				LEFT JOIN datapegawai dp ON dp.pegawaiid = a.id
				LEFT JOIN struktur.satkerpegawai b ON b.pegawaiid = a.id
				LEFT JOIN struktur.vw_satkertree_hr b1 ON b1.id = b.satkerid
				LEFT JOIN riwayatjabatan c ON c.pegawaiid = a.id
				LEFT JOIN struktur.jabatan c1 ON c1.id = b.jabatanid
				LEFT JOIN struktur.levelgrade c2 ON c2.id = b.levelid
				LEFT JOIN lokasi c3 ON c3.id = c.lokasiid
				LEFT JOIN statuspegawai c4 ON c4.id = c.statuspegawaiid
			WHERE 
				c.tglselesai IS NULL
				AND b1.code LIKE '" . $params['v_satkerid'] . "%'
				" . $where . "
			ORDER BY 
				c2.susunan
		";

		$q = $this->db->query("
			SELECT a.*
			FROM (" . $query . ") a
			OFFSET " . $params['v_start'] . " LIMIT " . $params['v_limit'] . "
		", $params);

		$q2 = $this->db->query("
			SELECT COUNT(*) as jml
			FROM (" . $query . ") a
		");

		$this->db->close();
		$result = array('success' => true, 'count' => $q2->first_row()->jml, 'data' => $q->result_array());
		return $result;
	}
	/* Report By Status Pegawai */

	/* Report By SDM */
	function getReportSDM($satkerid)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('report.sp_getreportstatuspegawai', array($satkerid));
		$result = array('success' => true, 'data' => $mresult['data']);
		return $result;
	}

	function reportListSdm($params)
	{
		$this->load->database();

		$cond_where = '';
		if ($params['v_satkerid'] == 'ECI') {
			if (!empty($params['v_golongan'])) {
				if ($params['v_golongan'] == 'bod') {
					$cond_where = " AND b1.code LIKE '" . $params['v_satkerid'] . "' || '%' AND c2.gol = '0'";
				} else {
					if ($params['v_golongan'] == 'null') {
						$cond_where = " AND b1.code LIKE '" . $params['v_satkerid'] . "' || '%'";
					} else {
						$cond_where = " AND b1.code LIKE '" . $params['v_satkerid'] . "' || '%' AND c2.gol = '" . $params['v_golongan'] . "'";
					}
				}
			} else {
				$cond_where = " AND b1.code LIKE '" . $params['v_satkerid'] . "' || '%'";
			}
		} else {
			if (!empty($params['v_golongan'])) {
				if ($params['v_golongan'] == 'bod') {
					$cond_where = " AND b1.code LIKE '" . $params['v_satkerid'] . "' || '%' AND c2.gol = '0'";
				} else {
					if ($params['v_golongan'] == 'null') {
						$cond_where = " AND b1.code LIKE '" . $params['v_satkerid'] . "' || '%'";
					} else {
						$cond_where = " AND b1.code LIKE '" . $params['v_satkerid'] . "' || '%' AND c2.gol = '" . $params['v_golongan'] . "'";
					}
				}
			} else {
				$cond_where = " AND b1.code LIKE '" . $params['v_satkerid'] . "' || '%'";
			}
		}

		$query = "
			SELECT 
				a.id as pegawaiid,
				a1.fullname AS nama,
				u.nik,
				b.satkerid as idsatker,
				b1.code as satkerid,
				b1.unitkerja,
				b1.direktorat,
				b1.divisi,
				b1.departemen,
				b1.seksi,
				b1.subseksi,
				b.jabatanid,
				c1.name as jabatan,
				b.levelid,
				c2.name as level,
				c2.gol,
				c2.susunan as idnew,
				dp.emailkantor,
				dp.notelp as telp,
				c.lokasiid,
				c3.name as lokasi,
				c4.name as statuspegawai,
				c.tglmulai,
				c.tglakhirkontrak,
				(DATE_PART('year', now()::date) - DATE_PART('year', c.tglmulai)) as masakerjaseluruhth,
				(DATE_PART('month', now()::date) - DATE_PART('month', c.tglmulai)) as masakerjaseluruhbl,
				e.pendidikan
			FROM 
				pegawai a
				LEFT JOIN users u ON u.id = a.userid
				LEFT JOIN datapegawai dp ON dp.pegawaiid = a.id
				LEFT JOIN namapegawai a1 ON a1.pegawaiid = a.id
				LEFT JOIN struktur.satkerpegawai b ON b.pegawaiid = a.id
				LEFT JOIN struktur.vw_satkertree_hr b1 ON b1.id = b.satkerid
				LEFT JOIN riwayatjabatan c ON c.pegawaiid = a.id
				LEFT JOIN struktur.jabatan c1 ON c1.id = b.jabatanid
				LEFT JOIN struktur.levelgrade c2 ON c2.id = b.levelid
				LEFT JOIN lokasi c3 ON c3.id = c.lokasiid
				LEFT JOIN statuspegawai c4 ON c4.id = c.statuspegawaiid
				LEFT JOIN vw_lastpendidikan e ON e.pegawaiid = a.id
			WHERE 
				c.tglselesai IS NULL
				" . $cond_where . "
			ORDER BY 
				c2.susunan
		";

		// var_dump($query);

		$q = $this->db->query("
			SELECT a.*
			FROM (" . $query . ") a
			OFFSET " . $params['v_start'] . " LIMIT " . $params['v_limit'] . "
		", $params);

		$q2 = $this->db->query("
			SELECT COUNT(*) as jml
			FROM (" . $query . ") a
		");

		$this->db->close();
		$result = array('success' => true, 'count' => $q2->first_row()->jml, 'data' => $q->result_array());
		return $result;
	}
	/* Report By SDM */

	/* Report by Location */
	function getLokasiByID($params)
	{
		$this->load->database();

		$cond_where = '';
		if (!empty($params['v_lokasiid'])) {
			$cond_where = " WHERE a.id = '" . $params['v_lokasiid'] . "' ORDER BY a.name";
		} else {
			$cond_where = 'ORDER BY a.name';
		}

		$q = $this->db->query("
			WITH ctelocation AS (
					SELECT 
						l.id as lokasiid, 
						l.code as kodelokasi, 
						COUNT(l.id) jml
					FROM 
						lokasi l
						LEFT JOIN riwayatjabatan vj ON l.id = vj.lokasiid
						WHERE 
							vj.tglselesai is null
					GROUP BY 
						l.id, 
						vj.tglselesai
				)
				SELECT 
					a.id as lokasiid, 
					a.name as lokasi, 
					a.code as kodelokasi, 
					COALESCE(b.jml, 0) jml
				FROM 
					lokasi a
					LEFT JOIN ctelocation b ON a.id = b.lokasiid
		" . $cond_where);

		$this->db->close();
		$result = array('success' => true, 'data' => $q->result_array());
		return $result;
	}

	function reportListLocation($params)
	{
		$this->load->database();

		$cond_where = '';
		if (!empty($params['v_lokasiid'])) {
			$cond_where = " WHERE vj.lokasiid = '" . $params['v_lokasiid'] . "' AND vj.tglselesai is null ORDER by loc.name";
		} else {
			$cond_where = 'WHERE vj.tglselesai is null ORDER by loc.name';
		}

		$query = "
			SELECT 
				p.id as pegawaiid, 
				np.fullname as nama, 
				u.nik, 
				vs.id as idsatker ,
				vs.code as satkerid,
				vs.direktorat,
				vs.divisi,
				vs.departemen,
				vs.seksi,
				vs.subseksi,
				sp.jabatanid, 
				j.name as jabatan, 
				sp.levelid, 
				l.name as level, 
				l.gol,
				dp.emailkantor, 
				dp.notelp as telp, 
				vj.lokasiid, 
				loc.name as lokasi, 
				s.name as statuspegawai,
				TO_CHAR(vj.tglmulai, 'DD/MM/YYYY') tglmulai, 
				TO_CHAR(vj.tglakhirkontrak, 'DD/MM/YYYY') tglakhirkontrak
			FROM pegawai p
				LEFT JOIN namapegawai np ON np.pegawaiid = p.id
				LEFT JOIN datapegawai dp ON dp.pegawaiid = p.id
				LEFT JOIN users u ON u.id = p.userid
				LEFT JOIN struktur.satkerpegawai sp ON sp.pegawaiid = p.id
				LEFT JOIN struktur.vw_satkertree_hr vs ON vs.id = sp.satkerid
				LEFT JOIN riwayatjabatan vj ON p.id = vj.pegawaiid
				LEFT JOIN struktur.jabatan j ON sp.jabatanid = j.id
				LEFT JOIN struktur.levelgrade l ON sp.levelid = l.id
				LEFT JOIN statuspegawai s ON vj.statuspegawaiid = s.id
				LEFT JOIN lokasi loc ON vj.lokasiid = loc.id
		" . $cond_where;

		$q = $this->db->query("
			SELECT a.*
			FROM (" . $query . ") a
			OFFSET " . $params['v_start'] . " LIMIT " . $params['v_limit'] . "
		", $params);

		$q2 = $this->db->query("
			SELECT COUNT(*) as jml
			FROM (" . $query . ") a
		");

		$this->db->close();
		$result = array('success' => true, 'count' => $q2->first_row()->jml, 'data' => $q->result_array());
		return $result;
	}
	/* Report by Location */

	/* Report by Level */
	function getGraphByLevel($params)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT 
				a.id as levelid, 
				a.name as level, 
				COALESCE(b.jml, 0) jml
			FROM 
				struktur.levelgrade a
				RIGHT JOIN (
					SELECT 
						l.id as levelid, 
						COUNT(vj.levelid) jml
					FROM 
						struktur.satkerpegawai vj
						LEFT JOIN struktur.levelgrade l ON vj.levelid = l.id
						left join struktur.vw_satkertree b on b.id = vj.satkerid
						left join riwayatjabatan rj on rj.pegawaiid = vj.pegawaiid
					WHERE 
						b.code LIKE ? || '%'
						AND rj.tglselesai is null
					GROUP BY 
						l.id
					) b ON a.id = b.levelid
			ORDER BY a.susunan, a.id
		", $params);
		$this->db->close();
		$result = array('success' => true, 'data' => $q->result_array());
		return $result;
	}

	function reportListLevel($params)
	{
		$this->load->database();

		$cond_where = '';
		if (!empty($params['v_levelid'])) {
			$cond_where = " 
			AND c2.id = '" . $params['v_levelid'] . "'
			";
		}

		$query = "
			 SELECT 
				a.id as pegawaiid,
				a1.fullname AS nama,
				u.nik,
				b.satkerid as idsatker,
				b1.code as satkerid,
				b1.direktorat,
				b1.divisi,
				b1.departemen,
				b1.seksi,
				b1.subseksi,
				c1.id as jabatanid,
				c1.name as jabatan,
				c2.id as levelid,
				c2.name as level,
				c2.gol,
				c2.susunan as idnew,
				dp.emailkantor,
				dp.notelp as telp,
				c.lokasiid,
				c3.name as lokasi,
				c4.name as statuspegawai
			FROM 
				pegawai a
				LEFT JOIN namapegawai a1 ON a1.pegawaiid = a.id
				LEFT JOIN users u ON u.id = a.userid
				LEFT JOIN datapegawai dp ON dp.pegawaiid = a.id
				LEFT JOIN struktur.satkerpegawai b ON b.pegawaiid = a.id
				LEFT JOIN struktur.vw_satkertree_hr b1 ON b1.id = b.satkerid
				LEFT JOIN riwayatjabatan c ON c.pegawaiid = a.id
				LEFT JOIN struktur.jabatan c1 ON c1.id = b.jabatanid
				LEFT JOIN struktur.levelgrade c2 ON c2.id = b.levelid
				LEFT JOIN lokasi c3 ON c3.id = c.lokasiid
				LEFT JOIN statuspegawai c4 ON c4.id = c.statuspegawaiid
			WHERE 
				c.tglselesai IS NULL
				AND b1.code LIKE '" . $params['v_satkerid'] . "' || '%'
				" . $cond_where . "
			ORDER BY 
				c2.susunan
		";

		$q = $this->db->query("
			SELECT a.*
			FROM (" . $query . ") a
			OFFSET " . $params['v_start'] . " LIMIT " . $params['v_limit'] . "
		", $params);

		$q2 = $this->db->query("
			SELECT COUNT(*) as jml
			FROM (" . $query . ") a
		");

		$this->db->close();
		$result = array('success' => true, 'count' => $q2->first_row()->jml, 'data' => $q->result_array());
		return $result;
	}
	/* Report by Level */

	/* Report by New Hired & Turn Over */
	function getReportListKetPegawai($params)
	{
		$mresult = $this->tp_connpgsql->callSpCount('report.sp_getreportketpegawai', $params, false);
		return $mresult;
	}

	function getGraphByKetPegawai($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('report.sp_getstatistikketpegawai', $params);
		return $mresult;
	}
	/* Report by New Hired & Turn Over */

	/* Report by Remind of Contract */
	function getReportEndOfContract($params)
	{
		$this->load->database();

		$query = "
			SELECT 
				a.id as pegawaiid,
				u.nik,
				a1.fullname AS nama,
				b.satkerid as idsatker,
				b1.code as satkerid,
				b1.unitkerja,
				b1.direktorat,
				b1.divisi,
				b1.departemen,
				b1.seksi,
				b1.subseksi,
				c3.name as level,
				c1.name as jabatan,
				c2.name as lokasi,
				to_char(c.tglmulai, 'DD/MM/YYYY') AS tglmulai,
				to_char(c.tglakhirkontrak, 'DD/MM/YYYY') AS tglakhirkontrak,
				EXTRACT(day FROM (c.tglakhirkontrak - now())) AS monthexp,
				c.statuspegawaiid,
				to_char(c.tglpermanent, 'DD/MM/YYYY') AS tglpermanent,
				c4.name as statuspegawai
			FROM 
				pegawai a
				LEFT JOIN users u ON u.id = a.userid
				LEFT JOIN datapegawai dp ON dp.pegawaiid = a.id
				LEFT JOIN namapegawai a1 ON a1.pegawaiid = a.id
				LEFT JOIN struktur.satkerpegawai b ON b.pegawaiid = a.id
				LEFT JOIN struktur.vw_satkertree_hr b1 ON b1.id = b.satkerid
				LEFT JOIN riwayatjabatan c ON c.pegawaiid = a.id
				LEFT JOIN struktur.jabatan c1 ON c1.id = b.jabatanid
				LEFT JOIN lokasi c2 ON c2.id = c.lokasiid
				LEFT JOIN struktur.levelgrade c3 ON c3.id = b.levelid
				LEFT JOIN statuspegawai c4 ON c4.id = c.statuspegawaiid
			WHERE 
				c.tglselesai IS NULL 
				AND c.statuspegawaiid <> '7'
				AND b1.code LIKE '" . $params['v_satkerid'] . "' || '%'
			ORDER BY 
				EXTRACT(day FROM (c.tglakhirkontrak - now())), 
				c.tglakhirkontrak
		";
		$q = $this->db->query("
			SELECT a.*
			FROM (" . $query . ") a
			OFFSET " . $params['v_start'] . " LIMIT " . $params['v_limit'] . "
		", $params);

		$q2 = $this->db->query("
			SELECT COUNT(*) as jml
			FROM (" . $query . ") a
		");

		$this->db->close();
		$result = array('success' => true, 'count' => $q2->first_row()->jml, 'data' => $q->result_array());
		return $result;
	}
	/* Report by Remind of Contract */

	/* Report by Gender */
	function statistikJenisKelamin($satkerid)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT
				sp.label AS labelid,
				CASE 
					WHEN sp.label = 'L' THEN 'Laki-laki' 
					WHEN sp.label = 'P' THEN 'Perempuan' 
					ELSE NULL 
				END AS label,
				COUNT ( P.pegawaiid ) AS jml 
			FROM
				( SELECT 'L' AS label UNION ALL SELECT 'P' AS label ) sp
				LEFT JOIN datapegawai P ON sp.label = P.jeniskelamin
				LEFT JOIN riwayatjabatan vj ON P.pegawaiid = vj.pegawaiid
				LEFT JOIN struktur.satkerpegawai b ON b.pegawaiid = P.pegawaiid 
				LEFT JOIN struktur.vw_satkertree c ON c.id = b.satkerid
			WHERE 
				vj.tglselesai is null
				AND c.code LIKE ? || '%' 
			GROUP BY 
				sp.label
		", array($satkerid));
		$this->db->close();
		$result = array('success' => true, 'data' => $q->result_array());
		return $result;
	}

	function reportListJenisKelamin($params)
	{
		$this->load->database();

		$query = "
			SELECT 
				a.id as pegawaiid,
				a1.fullname AS nama,
				u.nik,
				b.satkerid as idsatker,
				b1.code as satkerid,
				b1.unitkerja,
				b1.direktorat,
				b1.divisi,
				b1.departemen,
				b1.seksi,
				b1.subseksi,
				b.jabatanid,
				c1.name as jabatan,
				b.levelid,
				c2.name as level,
				c2.gol,
				c2.susunan as idnew,
				c.statuspegawaiid,
				c4.name as statuspegawai,
				dp.jeniskelamin,
				dp.emailpribadi as email,
				dp.emailkantor,
				dp.notelp telp,
				c.tglmulai,
				c.tglselesai,
				c3.name as lokasi,
				c.keterangan,
				CASE
					WHEN dp.jeniskelamin = 'L' THEN 'Laki-laki'
					WHEN dp.jeniskelamin = 'P' THEN 'Perempuan'
					ELSE NULL
				END AS gender
			FROM 
				pegawai a
				LEFT JOIN datapegawai dp ON dp.pegawaiid = a.id
				LEFT JOIN users u ON u.id = a.userid
				LEFT JOIN namapegawai a1 ON a1.pegawaiid = a.id
				LEFT JOIN struktur.satkerpegawai b ON b.pegawaiid = a.id
				LEFT JOIN struktur.vw_satkertree_hr b1 ON b1.id = b.satkerid
				LEFT JOIN riwayatjabatan c ON c.pegawaiid = a.id
				LEFT JOIN struktur.jabatan c1 ON c1.id = b.jabatanid
				LEFT JOIN struktur.levelgrade c2 ON c2.id = b.levelid
				LEFT JOIN lokasi c3 ON c3.id = c.lokasiid
				LEFT JOIN statuspegawai c4 ON c4.id = c.statuspegawaiid
			WHERE 
				c.tglselesai IS NULL
				AND b1.code LIKE '" . $params['v_satkerid'] . "' || '%'
				AND dp.jeniskelamin LIKE '" . $params['v_jeniskelamin'] . "' || '%'
			ORDER BY c2.susunan
			";

		$q = $this->db->query("
			SELECT a.*
			FROM (" . $query . ") a
			OFFSET " . $params['v_start'] . " LIMIT " . $params['v_limit'] . "
		", $params);

		$q2 = $this->db->query("
			SELECT COUNT(*) as jml
			FROM (" . $query . ") a
		");

		$this->db->close();
		$result = array('success' => true, 'count' => $q2->first_row()->jml, 'data' => $q->result_array());
		return $result;
		return $result;
	}
	/* Report by Gender */

	/* Report by Mutasi Promosi */
	function getMutasiPromosi($params)
	{
		$this->load->database();

		$query = "
			SELECT 
				rmp.pegawaiid,
				rmp.nourut,
				u.nik,
				np.fullname as namadepan,
				CASE WHEN rmp.jenismp = '1' THEN 'Mutasi' ELSE 'Promosi' END mutasipromosi,
				rmp.unitkerjaold AS unitkerja1,
				rmp.direktoratold AS direktorat1,
				rmp.divisiold AS divisi1,
				rmp.departemenold AS departemen1,
				rmp.seksiold AS seksi1,
				rmp.subseksiold AS subseksi1,
				rmp.unitkerjanew AS unitkerja2,
				rmp.direktoratnew AS direktorat2,
				rmp.divisinew AS divisi2,
				rmp.departemennew AS departemen2,
				rmp.seksinew AS seksi2,
				rmp.subseksinew AS subseksi2,
				rmp.jabatanold as jabatan1,
				rmp.jabatannew as jabatan2,
				rmp.levelold as level1,
				rmp.levelnew as level2,
				rmp.golold as golongan1,
				rmp.golnew as golongan2,
				rmp.lokasiold as lokasi1,
				rmp.lokasinew as lokasi2,
				TO_CHAR(rmp.tglawal, 'DD/MM/YYYY') tglmulai,
				TO_CHAR(rmp.tglakhir, 'DD/MM/YYYY') tglakhir,
				rmp.keterangan
			FROM 
				riwayatmp rmp
				LEFT JOIN pegawai p ON rmp.pegawaiid = p.id
				LEFT JOIN users u ON u.id = p.userid
				LEFT JOIN namapegawai np ON np.pegawaiid = p.id
			ORDER BY 
				rmp.tglawal DESC
		";

		$q = $this->db->query("
			SELECT a.*
			FROM (" . $query . ") a
			OFFSET " . $params['v_start'] . " LIMIT " . $params['v_limit'] . "
		", $params);

		$q2 = $this->db->query("
			SELECT COUNT(*) as jml
			FROM (" . $query . ") a
		");

		$this->db->close();
		$result = array('success' => true, 'count' => $q2->first_row()->jml, 'data' => $q->result_array());
		return $result;
	}
	/* Report by Mutasi Promosi */

	/* Report by Usia */
	function statistikUsiaPegawai($params)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT 
				sp.label AS labelid,
				CASE
					WHEN sp.label = '1' THEN 'n < 25'
					WHEN sp.label = '2' THEN '25 => n < 35'
					WHEN sp.label = '3' THEN '35 => n < 45'
					WHEN sp.label = '4' THEN '45 => n < 55'
					WHEN sp.label = '5' THEN '55 => n'
					ELSE NULL 
				END AS label,
				COUNT(vj.pegawaiid) jml
			FROM 
				( SELECT '1' AS label
						UNION ALL
					SELECT '2' AS label
						UNION ALL
					SELECT '3' AS label
						UNION ALL
					SELECT '4' AS label
						UNION ALL
					SELECT '5' AS label ) sp
				LEFT JOIN 
				( WITH 
					act1 AS ( SELECT
								CASE
									WHEN ( EXTRACT(YEAR FROM AGE(now(),c.tgllahir)))
									< 25 THEN '1'
									WHEN ( EXTRACT(YEAR FROM AGE(now(),c.tgllahir)))
									>= 25 AND ( EXTRACT(YEAR FROM AGE(now(),c.tgllahir)))
									< 35 THEN '2'
									WHEN ( EXTRACT(YEAR FROM AGE(now(),c.tgllahir)))
									>= 35 AND ( EXTRACT(YEAR FROM AGE(now(),c.tgllahir)))
									< 45 THEN '3'
									WHEN ( EXTRACT(YEAR FROM AGE(now(),c.tgllahir)))
									>= 45 AND ( EXTRACT(YEAR FROM AGE(now(),c.tgllahir)))
									< 55 THEN '4'
									WHEN ( EXTRACT(YEAR FROM AGE(now(),c.tgllahir)))
									>= 55 THEN '5'
									ELSE null 
								END labelid,
								a.id as pegawaiid
							FROM
								pegawai a
								LEFT JOIN riwayatjabatan b on a.id = b.pegawaiid
								LEFT JOIN datapegawai c on c.pegawaiid = a.id
								LEFT JOIN struktur.satkerpegawai d ON d.pegawaiid = a.id
								LEFT JOIN struktur.vw_satkertree e ON e.id = d.satkerid
							WHERE
								b.tglselesai is null
								AND e.code LIKE '" . $params['v_satkerid'] . "' || '%'
							ORDER BY 
								labelid ASC
							)
					SELECT 
						a.labelid,
						b.pegawaiid
					FROM 
						( SELECT 1 AS labelid
								UNION ALL
							SELECT 2 AS labelid
								UNION ALL
							SELECT 3 AS labelid
								UNION ALL
							SELECT 4 AS labelid
								UNION ALL
							SELECT 5 AS labelid ) a
						LEFT JOIN act1 b ON a.labelid = CAST(b.labelid AS INT)
				) vj ON CAST(sp.label AS INT) = vj.labelid
			GROUP BY sp.label
		", array($params));
		$this->db->close();
		$result = array('success' => true, 'data' => $q->result_array());
		return $result;
	}

	function reportListUsiaPegawai($params)
	{
		$this->load->database();

		$cond_where = '';
		if (!empty($params['v_labelid'])) {
			$cond_where = "
			WHERE 
				b.satkerid LIKE '" . $params['v_satkerid'] . "' || '%' 
				AND b.labelid = '" . $params['v_labelid'] . "'
			ORDER BY
				b.idnew,
				b.gol ASC";
		} else {
			$cond_where = "
			WHERE b.satkerid LIKE '" . $params['v_satkerid'] . "' || '%'
			ORDER BY
				b.idnew,
				b.gol ASC";
		}

		$query = "
			WITH act1 AS (
				SELECT
					CASE
						WHEN ( EXTRACT(YEAR FROM AGE(now(),dp.tgllahir)))
						< '25' THEN '1'
						WHEN ( EXTRACT(YEAR FROM AGE(now(),dp.tgllahir)))
						>= '25' AND ( EXTRACT(YEAR FROM AGE(now(),dp.tgllahir)))
						< '35' THEN '2'
						WHEN ( EXTRACT(YEAR FROM AGE(now(),dp.tgllahir)))
						>= '35' AND ( EXTRACT(YEAR FROM AGE(now(),dp.tgllahir)))
						<'45' THEN '3'
						WHEN ( EXTRACT(YEAR FROM AGE(now(),dp.tgllahir)))
						>= '45' AND ( EXTRACT(YEAR FROM AGE(now(),dp.tgllahir))) <
						'55' THEN '4'
						WHEN ( EXTRACT(YEAR FROM AGE(now(),dp.tgllahir)))
						>= '55' THEN '5'
						ELSE null 
					END labelid,
					a.id as pegawaiid,
					u.nik,
					np.fullname as namadepan,
					EXTRACT(YEAR FROM AGE(now(),dp.tgllahir)) as tahun,
					EXTRACT(MONTH FROM AGE(now(),dp.tgllahir)) as bulan,
					s.satkerid as idsatker,
					vs.code as satkerid,
					vs.unitkerja,
					vs.direktorat,
					vs.divisi,
					vs.departemen,
					vs.seksi,
					vs.subseksi,
					c.name as lokasi, 
					b.statuspegawaiid, 
					sp.name as statuspegawai,
					TO_CHAR(b.tglmulai, 'DD/MM/YYYY') tglmulai,
					TO_CHAR(dp.tgllahir, 'DD/MM/YYYY') tgllahir,
					j.name as jabatan,
					l.name as level,
					l.id as levelid,
					l.gol ,
					l.susunan as idnew
				FROM
					pegawai a
					LEFT JOIN users u ON u.id = a.userid
					LEFT JOIN namapegawai np ON np.pegawaiid = a.id
					LEFT JOIN datapegawai dp ON dp.pegawaiid = a.id
					LEFT JOIN struktur.satkerpegawai s ON s.pegawaiid = a.id
					LEFT JOIN struktur.vw_satkertree_hr vs ON vs.id = s.satkerid
					LEFT JOIN riwayatjabatan b on a.id = b.pegawaiid
					LEFT JOIN lokasi c on b.lokasiid = c.id
					LEFT JOIN statuspegawai sp ON b.statuspegawaiid = sp.id
					LEFT JOIN struktur.jabatan j ON s.jabatanid = j.id
					LEFT JOIN struktur.levelgrade l ON s.levelid = l.id
				WHERE
					b.tglselesai  is null
				ORDER BY 
					labelid ASC
			)
			SELECT 
				b.satkerid,
				b.nik,
				b.namadepan,
				b.direktorat,
				b.divisi,
				b.departemen,
				b.seksi,
				b.subseksi,
				b.lokasi,
				b.statuspegawai,
				b.labelid,
				b.tahun,
				b.bulan,
				b.tglmulai,
				b.tgllahir,
				b.jabatan,
				b.level,
				b.levelid,
				b.gol,
				b.idnew
			FROM 
				(
					SELECT 1 AS labelid
						UNION ALL
					SELECT 2 AS labelid
						UNION ALL
					SELECT 3 AS labelid
						UNION ALL
					SELECT 4 AS labelid
						UNION ALL
					SELECT 5 AS labelid
				) a
				LEFT JOIN act1 b ON a.labelid = CAST(b.labelid AS INT)
		" . $cond_where;

		$q = $this->db->query("
			SELECT a.*
			FROM (" . $query . ") a
			OFFSET " . $params['v_start'] . " LIMIT " . $params['v_limit'] . "
		", $params);

		$q2 = $this->db->query("
			SELECT COUNT(*) as jml
			FROM (" . $query . ") a
		");

		$this->db->close();
		$result = array('success' => true, 'count' => $q2->first_row()->jml, 'data' => $q->result_array());
		return $result;
	}
	/* Report by Usia */

	/* Report by Kader & Kader Group */
	function getReportListKader($params)
	{
		$this->load->database();

		$cond_where = '';
		if (!empty($params['v_levelid']) || !empty($params['v_lokasiid'])) {
			if (!empty($params['v_lokasiid']) && !empty($params['v_levelid'])) {
				$cond_where = " AND loc.id = '" . $params['v_lokasiid'] . "' AND l.id = '" . $params['v_levelid'] . "' ";
			} else if (!empty($params['v_levelid'])) {
				$cond_where = " AND l.id =  '" . $params['v_levelid'] . "' ";
			} else {
				$cond_where = " AND loc.id = '" . $params['v_lokasiid'] . "' ";
			}
		}

		if (!empty($params['v_pegawaiid'])) {
			$cond_where = " AND vj.pegawaiid IN('" . $params['v_pegawaiid'] . "') ";
		}

		$orderby = "
		ORDER BY 
			l.susunan, 
			l.gol ASC
		";

		$query = "
			WITH act1 AS (
				SELECT
					x.pegawaiid,
					string_agg ( DISTINCT concat ( x.nourut, '. ', x.jabatan, ' - ', x.perusahaan ), ', ' ) AS pengalamankerja 
				FROM
					riwayatexp x
				GROUP BY
					x.pegawaiid 
				),
				act2 AS (
				SELECT
					x.pegawaiid,
					string_agg ( DISTINCT concat ( x.nourut, '. ', x.jeniskursus ), ', ' ) AS kursus 
				FROM
					riwayatkursus x
				GROUP BY
					x.pegawaiid 
				),
				act3 AS (
				SELECT
					x.pegawaiid,
					string_agg ( DISTINCT concat ( x.nourut, '. ', x.kegiatan ), ', ' ) AS agp 
				FROM
					riwayatagp x
				GROUP BY
					x.pegawaiid 
				),
				act4 AS (
				SELECT
					x.pegawaiid,
					xx.name as relasi,
					x.nama,
					to_char( x.tgllahir, 'DD/MM/YYYY' ) AS tgllahir,
					x.pekerjaan,
					concat (
						EXTRACT(YEAR FROM AGE(now(),x.tgllahir)),
						' th ',
						EXTRACT(MONTH FROM AGE(now(),x.tgllahir)),
						' bln ' 
					) AS pasanganumur 
				FROM
					riwayatkeluarga x
					LEFT JOIN relasi xx ON xx.id = x.relasiid
				WHERE
					x.relasiid = 1
				),
				act5 AS (
				SELECT
					x.pegawaiid,
					string_agg ( DISTINCT concat ( ( x.relasiid - 1 ), '. ', x.nama ), ', ' ) AS anak 
				FROM
					riwayatkeluarga x
					LEFT JOIN relasi xx ON xx.id = x.relasiid
				WHERE
					xx.name ~~ '%Anak%' 
				GROUP BY
					x.pegawaiid 
				),
				act6 AS (
				SELECT
					vw.pegawaiid,
					MAX ( rw.pendidikanid ) AS pendidikanid,
					MAX ( rw.jurusan ) AS jurusan 
				FROM
					riwayatpendidikan rw
					LEFT JOIN riwayatjabatan vw ON rw.pegawaiid = vw.pegawaiid 
				WHERE
					vw.tglselesai is null 
				GROUP BY
					vw.pegawaiid 
				ORDER BY
					vw.pegawaiid 
				) 
			SELECT
				b1.satkerid as idsatker,
				b2.code as satkerid,
				vj.pegawaiid,
				u.nik,
				z.fullname AS nama,
				to_char( dp.tgllahir, 'DD/MM/YYYY' ) AS krtgllahir,
				sh.namashio as shio,
				sh.unsurshio as unsur,
				A.pengalamankerja,
				b.kursus,
				C.agp,
				d.nama AS pasangan,
				d.tgllahir,
				d.pekerjaan,
				e.anak,
				concat ( pen.code, ' - ', f.jurusan ) AS pendidikan,
				b1.levelid,
				l.name as level,
				j.name as jabatan,
				b2.divisi,
				b2.direktorat,
				b2.departemen,
				b2.seksi,
				b2.subseksi,
				loc.name as lokasi,
				loc.id as lokasiid,
				concat (
					EXTRACT(YEAR FROM AGE(now(),dp.tgllahir)),
					' th ',
					EXTRACT(MONTH FROM AGE(now(),dp.tgllahir)),
					' bln ' 
				) AS usia,
				concat (
					EXTRACT(YEAR FROM AGE(now(),vj.tglmulai)),
					' th ',
					EXTRACT(MONTH FROM AGE(now(),vj.tglmulai)),
					' bln ' 
				) AS lamakerja,
				d.pasanganumur,
				dp.foto,
				l.gol,
				vj.tglmulai 
			FROM
				riwayatjabatan vj
				LEFT JOIN datapegawai dp ON dp.pegawaiid = vj.pegawaiid
				LEFT JOIN shio sh ON sh.id = dp.shioid
				LEFT JOIN struktur.satkerpegawai b1 ON b1.pegawaiid = vj.pegawaiid
				LEFT JOIN struktur.vw_satkertree_hr b2 ON b2.id = b1.satkerid
				LEFT JOIN act1 A ON vj.pegawaiid = A.pegawaiid
				LEFT JOIN act2 b ON vj.pegawaiid = b.pegawaiid
				LEFT JOIN act3 C ON vj.pegawaiid = C.pegawaiid
				LEFT JOIN act4 d ON vj.pegawaiid = d.pegawaiid
				LEFT JOIN act5 e ON vj.pegawaiid = e.pegawaiid
				LEFT JOIN act6 f ON vj.pegawaiid = f.pegawaiid
				LEFT JOIN pegawai P ON vj.pegawaiid = P.id
				LEFT JOIN users u ON u.id = P.userid
				LEFT JOIN struktur.levelgrade l ON b1.levelid = l.id
				LEFT JOIN struktur.jabatan j ON b1.jabatanid = j.id
				LEFT JOIN pendidikan pen ON f.pendidikanid = pen.id
				LEFT JOIN lokasi loc ON vj.lokasiid = loc.id
				LEFT JOIN namapegawai z ON z.pegawaiid = P.id 
			WHERE
				vj.tglselesai is null
				AND b2.code LIKE '" . $params['v_satkerid'] . "' || '%'
		" . $cond_where . $orderby;

		$q = $this->db->query("
			SELECT a.*
			FROM (" . $query . ") a
			OFFSET " . $params['v_start'] . " LIMIT " . $params['v_limit'] . "
		", $params);

		$q2 = $this->db->query("
			SELECT COUNT(*) as jml
			FROM (" . $query . ") a
		");

		$this->db->close();
		$result = array('success' => true, 'count' => $q2->first_row()->jml, 'data' => $q->result_array());
		return $result;
	}
	/* Report by Kader & Kader Group */

	/* Report by Ultah */
	function getReportUlangtahun($params)
	{
		$this->load->database();

		$query = "
			SELECT
				p.id as pegawaiid ,
				u.nik ,
				np.fullname as namadepan ,
				vs.id as idsatker ,
				vs.code as satkerid ,
				vs.unitkerja ,
				vs.direktorat ,
				vs.divisi ,
				vs.departemen ,
				vs.seksi ,
				vs.subseksi ,
				dp.tempatlahir ,
				to_char(dp.tgllahir, 'DD/MM/YYYY') as tgllahir ,
				l.name as lokasi ,
				CASE WHEN to_char(dp.tgllahir, 'DD/MM') = to_char(CURRENT_DATE, 'DD/MM') THEN '1' ELSE NULL END birthday
			FROM
				pegawai p
				LEFT JOIN users u ON u.id = p.userid
				LEFT JOIN namapegawai np ON np.pegawaiid = p.id
				LEFT JOIN datapegawai dp ON dp.pegawaiid = p.id
				LEFT JOIN struktur.satkerpegawai sp ON sp.pegawaiid = p.id
				LEFT JOIN struktur.vw_satkertree_hr vs ON vs.id = sp.satkerid
				LEFT JOIN riwayatjabatan rj ON rj.pegawaiid = p.id
				LEFT JOIN lokasi l ON l.id = rj.lokasiid
			WHERE 
				rj.tglselesai IS NULL
				AND vs.code LIKE '" . $params['v_satkerid'] . "%'
				AND ( TO_CHAR(dp.tgllahir, 'DD') >= '" . $params['v_hari'] . "' AND TO_CHAR(dp.tgllahir, 'DD') <= '31' )
				AND ( TO_CHAR(dp.tgllahir, 'MM') >= '" . $params['v_bulan'] . "' AND TO_CHAR(dp.tgllahir, 'MM') <= '" . $params['v_bulan'] . "' )
			ORDER BY 
				birthday , 
				dp.tgllahir ASC
			";

		$q = $this->db->query("
			SELECT a.*
			FROM (" . $query . ") a
			OFFSET " . $params['v_start'] . " LIMIT " . $params['v_limit'] . "
		", $params);

		$q2 = $this->db->query("
			SELECT COUNT(*) as jml
			FROM (" . $query . ") a
		");

		$this->db->close();
		$result = array('success' => true, 'count' => $q2->first_row()->jml, 'data' => $q->result_array());
		return $result;
	}
	/* Report by Ultah */

	/* Report Acting As Jangan Hapus
	function getReportActingAs($params)
	{
		$this->load->database();
		$q = $this->db->query("
			WITH act1 AS ( SELECT a.id,count(a.id) jml1, ra.actingas AS ke1
                        FROM month a
                        LEFT JOIN riwayatactingas ra ON a.id = date_part('month', tglmulai)
                        WHERE ra.actingas IN ('1') AND ra.satkerid1 LIKE '" . $params['v_satkerid'] . "' || '%'
                        GROUP BY a.id,ra.actingas
                        ORDER BY a.id ASC
                     ),
              act2 AS ( SELECT a.id,count(a.id) jml2, ra.actingas AS ke2
                        FROM month a
                        LEFT JOIN riwayatactingas ra ON a.id = date_part('month', tglmulai)
                        WHERE ra.actingas IN ('2') AND ra.satkerid1 LIKE '" . $params['v_satkerid'] . "' || '%'
                        GROUP BY a.id, ra.actingas
                        ORDER BY a.id ASC
                     ),
              act3 AS ( SELECT a.id,count(a.id) jml3, ra.actingas AS ke3
                        FROM month a
                        LEFT JOIN riwayatactingas ra ON a.id = date_part('month', tglmulai)
                        WHERE ra.actingas IN ('3') AND ra.satkerid1 LIKE '" . $params['v_satkerid'] . "' || '%'
                        GROUP BY a.id, ra.actingas
                        ORDER BY a.id ASC
                     ),
              act4 AS ( SELECT a.id,count(a.id) jml4, ra.actingas AS ke4
                        FROM month a
                        LEFT JOIN riwayatactingas ra ON a.id = date_part('month', tglmulai)
                        WHERE ra.actingas IN ('4') AND ra.satkerid1 LIKE '" . $params['v_satkerid'] . "' || '%'
                        GROUP BY a.id, ra.actingas
                        ORDER BY a.id ASC
                     )
        SELECT *
        FROM month m
        LEFT JOIN act1 a ON m.id = a.id
		LEFT JOIN act2 b ON m.id = b.id
        LEFT JOIN act3 c ON m.id = c.id
        LEFT JOIN act4 d ON m.id = d.id
        	UNION ALL
		SELECT NULL,'Total',NULL, SUM(COALESCE(a.jml1, 0)) AS status1,NULL,NULL,SUM(COALESCE(b.jml2, 0)) AS status2,NULL,NULL,SUM(COALESCE(c.jml3, 0)) AS status3,NULL,NULL,SUM(COALESCE(d.jml4, 0)) AS status4,NULL
		FROM month m
		LEFT JOIN act1 a ON m.id = a.id
		LEFT JOIN act2 b ON m.id = b.id
        LEFT JOIN act3 c ON m.id = c.id
        LEFT JOIN act4 d ON m.id = d.id
		");
		$this->db->close();
		$result = array('success' => true, 'data' => $q->result_array());
		return $result;
	}

	function getReportActingAsBySatker($params)
	{
		$this->load->database();

		$cond_where = '';
		if (!empty($params['v_satkerid'])) {
			$cond_where = " WHERE rj.satkerid LIKE '" . $params['v_satkerid'] . "' || '%' ORDER BY rmp.tglmulai DESC";
		}

		$query = "
			SELECT rmp.pegawaiid,p.nik,p.namadepan,rmp.nourut,rmp.actingas, rj.satkerid,
	        public.fnsatkerlevel(rmp.satkerid1,'1') AS direktorat1,
	        public.fnsatkerlevel(rmp.satkerid1,'2') AS divisi1,
	        public.fnsatkerlevel(rmp.satkerid1,'3') AS departemen1,
	        public.fnsatkerlevel(rmp.satkerid1,'4') AS seksi1,
	        public.fnsatkerlevel(rmp.satkerid1,'5') AS subseksi1,
	        public.fnsatkerlevel(rmp.satkerid2,'1') AS direktorat2,
	        public.fnsatkerlevel(rmp.satkerid2,'2') AS divisi2,
	        public.fnsatkerlevel(rmp.satkerid2,'3') AS departemen2,
	        public.fnsatkerlevel(rmp.satkerid2,'4') AS seksi2,
	        public.fnsatkerlevel(rmp.satkerid2,'5') AS subseksi2,
	        rmp.jabatan1 as jabatanid1,b.jabatan as jabatan1,rmp.jabatan2 as jabatanid2,c.jabatan as jabatan2,
	        rmp.levelid1 as levelid1,d.level as level1,rmp.levelid2 as levelid2,e.level as level2,
	        rmp.golongan1,rmp.golongan2,
	        rmp.lokasi1 as lokasiid1,f.lokasi as lokasi1,rmp.lokasi2 as lokasiid2,g.lokasi as lokasi2,
	        rmp.satkerid1 as satkerid1,s.satker as satker1,rmp.satkerid2 as satkerid2,st.satker as satker2,
	        rmp.tglmulai,rmp.tglakhir,rmp.keterangan
        FROM riwayatactingas rmp
	        LEFT JOIN jabatan b ON rmp.jabatan1 = b.jabatanid
	        LEFT JOIN jabatan c ON rmp.jabatan2 = c.jabatanid
	        LEFT JOIN level d ON CAST((rmp.levelid1)AS INT) = d.levelid
	        LEFT JOIN level e ON CAST((rmp.levelid2)AS INT) = e.levelid
	        LEFT JOIN lokasi f ON CAST((rmp.lokasi1)AS INT) = f.lokasiid
	        LEFT JOIN lokasi g ON CAST((rmp.lokasi2)AS INT) = g.lokasiid
	        LEFT JOIN satker s ON rmp.satkerid1 = s.satkerid
	        LEFT JOIN satker st ON rmp.satkerid2 = st.satkerid
	        LEFT JOIN pegawai p ON rmp.pegawaiid = p.pegawaiid
	        LEFT JOIN riwayatjabatan rj ON rmp.pegawaiid = rj.pegawaiid
		" . $cond_where;

		$q = $this->db->query("
			SELECT a.*
			FROM (" . $query . ") a
			OFFSET " . $params['v_start'] . " LIMIT " . $params['v_limit'] . "
		", $params);

		$q2 = $this->db->query("
			SELECT COUNT(*) as jml
			FROM (" . $query . ") a
		");

		$this->db->close();
		$result = array('success' => true, 'count' => $q2->first_row()->jml, 'data' => $q->result_array());
		return $result;
	}
	Report Acting As Jangan Hapus */
}
