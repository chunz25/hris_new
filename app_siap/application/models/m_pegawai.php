<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class m_pegawai extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	/* Panel Identitas */
	function getListPegawai($params)
	{
		$mresult = $this->tp_connpgsql->callSpCount('sp_getdatapegawai', $params, false);
		return $mresult;
	}

	function get_address_by_kodepos($kodepos)
	{
		$this->db->select('id, kelurahan, kecamatan, kota');
		$this->db->from('alamat.vw_alamattree');
		$this->db->where('pk', $kodepos);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->row_array(); // Return the first row as an associative array
		} else {
			return false; // No data found
		}
	}

	function getPegawaiByID($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('report.sp_getpegawaibyid', $params);
		return $mresult;
	}

	function tambahPegawai($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT public.sp_addpegawai(
				?,?,?,?,?,?,?,?,?,?,?,?,
				?,?,?,?,?,?,?,?,?,?,
				?,?,?,?,?,
				?,?,?,?,?,
				?,?,?,?,?,
				?,?,?,?,?,
				?,?,?,?,?,
				?,?,?,?,?
			);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function ubahPegawai($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_updpegawai(
				?,?,?,?,?,?,?,?,?,?,
				?,?,?,?,?,?,?,?,?,?,
				?,?,?,?,?,?,?,?,?,?,
				?,?,?,?,?,?,?,?,?,?,
				?
				);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
		// var_dump($this->db->trans_status());
	}

	function getreportpegawaiByID($params)
	{
		$this->load->database();

		$cond_where = '';
		if (!empty($params['v_pegawaiid'])) {
			$cond_where = " WHERE p.id = " . $params['v_pegawaiid'];
		}

		$q = $this->db->query("
			SELECT
				u.nik ,
				p.id as pegawaiid ,
				np.fullname ,
				np.frontname as namadepan ,
				btrim(concat(np.midname, ' ', np.endname)) AS namabelakang ,
				st.name as statuspegawai ,
				j.name as jabatan ,
				dp.foto ,
				vs.unitkerja ,
				vs.direktorat ,
				vs.divisi ,
				vs.departemen ,
				vs.seksi ,
				vs.subseksi ,
				l.name as level ,
				lok.name as lokasi ,
				to_char(rj.tglmulai, 'DD/MM/YYYY') AS tglmulai ,
				to_char(rj.tglselesai, 'DD/MM/YYYY') AS tglselesai ,
				CASE
					WHEN rj.tglselesai IS NOT NULL THEN EXTRACT(YEAR FROM age(rj.tglselesai, rj.tglmulai))
					ELSE EXTRACT(YEAR FROM age(now(), rj.tglmulai))
				END AS tahun ,
				CASE
					WHEN rj.tglselesai IS NOT NULL THEN EXTRACT(MONTH FROM age(rj.tglselesai, rj.tglmulai))
					ELSE EXTRACT(MONTH FROM age(now(), rj.tglmulai))
				END AS bulan ,
				dp.tempatlahir ,
				to_char(dp.tgllahir, 'DD/MM/YYYY') AS tgllahir ,
				EXTRACT(YEAR FROM age(now(), dp.tgllahir)) usia ,
				CASE 
					WHEN dp.jeniskelamin = 'P' THEN 'Wanita'
					WHEN dp.jeniskelamin = 'L' THEN 'Pria'
					ELSE 'N/A'
				END AS jeniskelamin ,
				dp.alamatktp ,
				dp.alamatdom as alamat ,
				gd.name as goldarah ,
				dp.noktp ,
				a.name as agama ,
				dp.nohp as hp ,
				sn.name as statusnikah ,
				s.namashio as shio ,
				s.unsurshio as unsur ,
				dp.bb as beratbadan ,
				dp.tb as tinggibadan ,
				dp.bpjskes ,
				dp.bpjsnaker ,
				CASE
					WHEN vv.manager_name = 'Tidak ada atasan' THEN va.manager_nik
					ELSE vv.manager_nik
				END AS atasannik ,
				CASE
					WHEN vv.manager_name = 'Tidak ada atasan' THEN va.manager_name
					ELSE vv.manager_name
				END AS atasannama
			FROM
				pegawai p
				LEFT JOIN users u ON u.id = p.userid
				LEFT JOIN namapegawai np ON np.pegawaiid = p.id
				LEFT JOIN datapegawai dp ON dp.pegawaiid = p.id
				LEFT JOIN goldarah gd ON gd.id = dp.goldarahid
				LEFT JOIN agama a ON a.id = dp.agamaid
				LEFT JOIN statusnikah sn ON sn.id = dp.statusnikahid
				LEFT JOIN shio s ON s.id = dp.shioid
				LEFT JOIN riwayatjabatan rj ON rj.pegawaiid = p.id
				LEFT JOIN lokasi lok ON lok.id = rj.lokasiid
				LEFT JOIN statuspegawai st ON st.id = rj.statuspegawaiid
				LEFT JOIN struktur.satkerpegawai sp ON sp.pegawaiid = p.id
				LEFT JOIN struktur.vw_satkertree_hr vs ON vs.id = sp.satkerid
				LEFT JOIN struktur.jabatan j ON j.id = sp.jabatanid
				LEFT JOIN struktur.levelgrade l ON l.id = sp.levelid
				LEFT JOIN struktur.vw_verifer vv ON vv.pegawaiid = p.id
				LEFT JOIN struktur.vw_approver va ON va.pegawaiid = p.id
		" . $cond_where);

		$this->db->close();
		$result = array('success' => true, 'data' => $q->result_array());
		return $result;
	}

	function getdataprint($params2)
	{
		$this->load->database();
		$q = $this->db->query(
			"
			SELECT 
				u.id as userid, 
				u.nik as username, 
				np.fullname as nama, 
				p.id as pegawaiid, 
				cast(now() as date) tanggal 
			FROM 
				users u
				LEFT JOIN pegawai p ON p.userid = u.id
				LEFT JOIN namapegawai np ON np.pegawaiid = p.id 
			WHERE 
				p.id = " . $params2['v_pegawaiid'],
			array($params2)
		);
		$this->db->close();
		$mresult = array('success' => true, 'data' => $q->result_array());
		return $mresult;
	}
	/* Panel Identitas */

	/* Riwayat Jabatan */
	function getRiwayatJabatan($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('report.sp_getriwayatjabatan', $params);
		return $mresult;
	}

	function addRiwayatJabatan($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_addriwayatjabatan(?,?,?,?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatJabatan($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_updriwayatjabatan(?,?,?,?,?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatJabatan($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_delriwayatjabatan(?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	/* Riwayat Jabatan */

	/* Data Keluarga */
	function getRiwayatKeluarga($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('report.sp_getriwayatkeluarga', $params);
		return $mresult;
	}

	function addRiwayatKeluarga($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_addriwayatkeluarga(?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatKeluarga($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_updriwayatkeluarga(?,?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatKeluarga($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_delriwayatkeluarga(?,?);
		", array(strval($params['pegawaiid']), $params['nourut']));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function getRiwayatKeluargaInti($params)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT
				A.pegawaiid,
				A.nourut,
				b.name as relasi,
				A.nama,
				A.jeniskelamin,
				TO_CHAR( A.tgllahir, 'DD/MM/YYYY' ) tgllahir,
				A.pendidikan,
				A.pekerjaan,
				A.foto,
				A.tempatlahir as tmptlahir,
				A.alamat
			FROM
				riwayatkeluarga A
				LEFT JOIN relasi b ON b.id = A.relasiid
			WHERE
				A.relasiid BETWEEN 1 AND 7
				AND A.pegawaiid = '" . $params['v_pegawaiid'] . "'
		", array($params));
		$this->db->close();
		$mresult = array('success' => true, 'data' => $q->result_array());
		return $mresult;
	}

	function getRiwayatKeluargaBesar($params)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT
				A.pegawaiid,
				A.nourut,
				b.name as relasi,
				A.nama,
				A.jeniskelamin,
				TO_CHAR( A.tgllahir, 'DD/MM/YYYY' ) tgllahir,
				A.pendidikan,
				A.pekerjaan,
				A.foto,
				A.tempatlahir as tmptlahir,
				A.alamat
			FROM
				riwayatkeluarga A
				LEFT JOIN relasi b ON b.id = A.relasiid
			WHERE
				A.relasiid NOT BETWEEN 1 AND 7
				AND A.pegawaiid = '" . $params['v_pegawaiid'] . "'
		", array($params));
		$this->db->close();
		$mresult = array('success' => true, 'data' => $q->result_array());
		return $mresult;
	}
	/* Data Keluarga */

	/* Data Pendidikan */
	function getRiwayatPendidikan($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('report.sp_getriwayatpendidikan', $params);
		return $mresult;
	}

	function addRiwayatPendidikan($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_addriwayatpendidikan(?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatPendidikan($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_updriwayatpendidikan(?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatPendidikan($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_delriwayatpendidikan(?,?);
		", array(strval($params['pegawaiid']), $params['nourut']));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	/* Data Pendidikan */

	/* Data Pengalaman Kerja */
	function getRiwayatPengalamanKerja($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('report.sp_getriwayatpengalamankerja', $params);
		return $mresult;
	}

	function addRiwayatPengalamanKerja($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_addriwayatpengalamankerja(?,?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatPengalamanKerja($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_updriwayatpengalamankerja(?,?,?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatPengalamanKerja($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_delriwayatpengalamankerja(?,?);
		", array(strval($params['pegawaiid']), $params['nourut']));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	/* Data Pengalaman Kerja */

	/* Data Rekening */
	function getRiwayatRekening($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('report.sp_getriwayatrekening', $params);
		return $mresult;
	}

	function addRiwayatRekening($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_addriwayatrekening(?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatRekening($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_updriwayatrekening(?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatRekening($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_delriwayatrekening(?,?);
		", array(strval($params['pegawaiid']), $params['nourut']));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	/* Data Rekening */

	/* Data Training */
	function getRiwayatKursus($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('report.sp_getriwayatkursus', $params);
		return $mresult;
	}

	function addRiwayatKursus($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_addriwayatkursus(?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatKursus($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_updriwayatkursus(?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatKursus($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_delriwayatkursus(?,?);
		", array(strval($params['pegawaiid']), $params['nourut']));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	/* Data Training */

	/* Data Bahasa */
	function getRiwayatBahasa($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('report.sp_getriwayatbahasa', $params);
		return $mresult;
	}

	function addRiwayatBahasa($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_addriwayatbahasa(?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatBahasa($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_updriwayatbahasa(?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatBahasa($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_delriwayatbahasa(?,?);
		", array(strval($params['pegawaiid']), $params['nourut']));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	/* Data Bahasa */

	/* Data Riwayat Penyakit */
	function getRiwayatPenyakit($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('report.sp_getriwayatpenyakit', $params);
		return $mresult;
	}

	function addRiwayatPenyakit($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_addriwayatpenyakit(?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatPenyakit($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_updriwayatpenyakit(?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatPenyakit($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_delriwayatpenyakit(?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	/* Data Riwayat Penyakit */

	/* Data Kegiatan AGP */
	function getRiwayatAGP($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('report.sp_getriwayatagp', $params);
		return $mresult;
	}

	function addRiwayatAGP($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_addriwayatagp(?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatAGP($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_updriwayatagp(?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatAGP($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_delriwayatagp(?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	/* Data Kegiatan AGP */

	/* Data Indisipliner */
	function getRiwayatIndiplisiner($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('report.sp_getriwayatindiplisiner', $params);
		return $mresult;
	}

	function addRiwayatIndiplisiner($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_addriwayatind(?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatIndiplisiner($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_updriwayatind(?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatIndiplisiner($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_delriwayatindiplisiner(?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	/* Data Indisipliner */

	/* Data KPI */
	function getRiwayatPA($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('report.sp_getriwayatpa', $params);
		return $mresult;
	}

	function addRiwayatPA($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_addriwayatpa(?,?,?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatPA($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_updriwayatpa(?,?,?,?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatPA($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_delriwayatpa(?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	/* Data KPI */

	/* Data Catatan Tambahan */
	function getRiwayatCatatanTambahan($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('report.sp_getriwayatcatatantambahan', $params);
		return $mresult;
	}

	function addRiwayatCatatanTambahan($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_addriwayatcatatantambahan(?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatCatatanTambahan($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_updriwayatcatatantambahan(?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatCatatanTambahan($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_delriwayatcatatantambahan(?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	/* Data Catatan Tambahan */

	/* Data Riwayat Keahlian */
	function getRiwayatKeahlian($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('report.sp_getriwayatkeahlian', $params);
		return $mresult;
	}

	function addRiwayatKeahlian($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_addriwayatkeahlian(?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatKeahlian($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_updriwayatkeahlian(?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatKeahlian($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_delriwayatkeahlian(?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	/* Data Riwayat Keahlian */


	/* Data Acting As */
	function getActingAs($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('report.sp_getactingas', $params);
		return $mresult;
	}

	function getNik($params)
	{
		$p = $params['v_pegawaiid'];
		$this->load->database();
		$q = $this->db->query(" SELECT a.nik from users a left join pegawai b on b.userid = a.id where b.id = $p ");
		$this->db->close();
		$result = array('success' => true, 'data' => $q->result_array());
		return $result;
	}

	function addRiwayatActingAs($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_addriwayatactingas(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatActingAs($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_updriwayatactingas(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatJabatanAct($params2)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_updriwayatjabatanact(?,?,?,?,?,?,?);
		", $params2);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatActingAs($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_delriwayatactingas(?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	/* Data Acting As */

	/* Data Mutasi Promosi */
	function getMutasiPromosi($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('report.sp_getmutasipromosi', $params);
		return $mresult;
	}

	function addRiwayatMutasiPromosi($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_addriwayatmutasipromosi(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatMutasiPromosi($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_updriwayatmutasipromosi(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function updRiwayatJabatanMP($params2)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_updriwayatjabatanrmp(?,?,?,?,?,?,?);
		", $params2);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function delRiwayatMutasiPromosi($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT report.sp_delriwayatmutasipromosi(?,?);
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function getMutasiPromosiByNik($params)
	{
		$this->load->database();

		$cond_where = '';
		if (!empty($params['v_nik'])) {
			$cond_where = " AND u.nik = '" . $params['v_nik'] . "'";
		}

		$q = $this->db->query("
		SELECT
			p.id as pegawaiid ,
			vj.satkerid as idsatker,
			s.code as satkerid,
			j.name as jabatan,
			vj.jabatanid,
			l.name as level,
			vj.levelid,
			l.gol,
			loc.id as lokasikerja,
			loc.name as lokasi,
			s.direktorat,
			s.divisi,
			s.departemen,
			s.seksi,
			s.subseksi 
		FROM
			pegawai p
			LEFT JOIN struktur.satkerpegawai vj ON p.id = vj.pegawaiid
			LEFT JOIN struktur.vw_satkertree_hr s ON s.id = vj.satkerid
			LEFT JOIN struktur.jabatan j ON j.id = vj.jabatanid
			LEFT JOIN struktur.levelgrade l ON l.id = vj.levelid
			LEFT JOIN riwayatjabatan rj ON rj.pegawaiid = p.id
			LEFT JOIN lokasi loc ON loc.id = rj.lokasiid
			LEFT JOIN users u ON u.id = p.userid
		WHERE 
			rj.tglselesai is null
	
		" . $cond_where);

		$this->db->close();
		$result = array('success' => true, 'data' => $q->result_array());
		return $result;
	}
	/* Data Mutasi Promosi */

	function getListPegawai2($params)
	{
		$mresult = $this->tp_connpgsql->callSpCount('sp_getdatapegawai2', $params, false);
		return $mresult;
	}

	function getListPegawai1($params)
	{
		$mresult1 = $this->tp_connpgsql->callSpCount('sp_getdatapegawaichunz1', $params, false);
		return $mresult1;
	}

	function getListPegawai10($params)
	{
		$mresult10 = $this->tp_connpgsql->callSpCount('sp_getdatapegawaichunz10', $params, false);
		return $mresult10;
	}



	function updAtasan()
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
				UPDATE satker SET kepalaid = NULL, kepalajabatan = NULL, statusjabatan = NULL
				WHERE satkerid IN (
			    SELECT a.satkerid
				FROM vwjabatanterakhir a
				LEFT JOIN pegawai b ON a.pegawaiid = b.pegawaiid
				LEFT JOIN satker c ON a.satkerid = c.satkerid
				WHERE a.keteranganpegawai = '2' AND a.pegawaiid = c.kepalaid
				ORDER BY a.tglselesai desc
				)
			");
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
}
