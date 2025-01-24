<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class m_history extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getListPegawai($params)
	{
		$mresult = $this->tp_connpgsql->callSpCount('kehadiran.sp_getdatahistorypegawai', $params, false);
		return $mresult;
	}
	
	function getListHistoryCuti($params)
	{
		$mresult = $this->tp_connpgsql->callSpCount('kehadiran.sp_getlistcutipeg', $params, false);

		$data = array();
		foreach ($mresult['data'] as $r) {
			$r['fileexist'] = false;
			if (!empty($r['files'])) {
				$filePath = config_item('eservices_upload_dok_path') . $r['files'];
				if (file_exists($filePath) && is_file($filePath)) {
					$r['fileexist'] = true;
				} else {
					$r['fileexist'] = false;
				}
			}
			$data[] = $r;
		}

		$result = array('success' => true, 'count' => $mresult['count'], 'data' => $data);
		return $result;
	}

	function deleteCuti($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT kehadiran.sp_deletecuti(?,?);		
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function getCutiById($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('kehadiran.sp_getcutibyid', $params);
		return $mresult['firstrow'];
	}

	function getDetailPengajuanCuti($pengajuanid)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('kehadiran.sp_getdetailpengajuancuti', array($pengajuanid));
		return $mresult['data'];
	}

	function getComboJenisCuti($jeniscutiid = '')
	{
		$this->load->database();
		$whereClause = '';
		if (!empty($jeniscutiid)) {
			$whereClause = " WHERE jeniscutiid = '" . $jeniscutiid . "' ";
		}
		$sql = "
			SELECT id, name AS text 
			FROM kehadiran.cuti_jenis_hdr 
			" . $whereClause . "
			ORDER BY id
		";

		$q = $this->db->query($sql);
		$this->db->close();
		return $q->result_array();
	}

	function getHariLibur()
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT DISTINCT TO_CHAR(tgl, 'YYYY-MM-DD') AS tgl FROM harilibur WHERE tgl >= TO_DATE('01/01/2018','DD/MM/YYYY') ORDER BY tgl ASC		
		");
		$this->db->close();

		return $q->result_array();
	}

	// update status batal cuti
	function updStatusCuti($params)
	{
		$this->db->where('pengajuanid', $params['pengajuanid']);
		$this->db->update('kehadiran.cuti_hdr', $params);
	}

	function getInfoPegawai($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('public.sp_getinfopegawai', $params);
		return $mresult;
	}

	function addAlasan($batalCuti, $pengajuanid)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query(
			"
			UPDATE kehadiran.cuti_dtl set alasancuti = '" . $batalCuti . "' WHERE pengajuanid = $pengajuanid",
			array($pengajuanid)
		);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function getComboStatusCuti()
	{
		$this->load->database();
		$q = $this->db->query("select * from kehadiran.cuti_status ORDER BY id");
		$this->db->close();
		return $q->result_array();
	}
}
