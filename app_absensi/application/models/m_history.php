<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class m_history extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function getListPegawai($params)
	{
		try {
			return $this->tp_connpgsql->callSpCount('kehadiran.sp_getdatahistorypegawai', $params, false);
		} catch (Exception $e) {
			log_message('error', 'getListPegawai Error: ' . $e->getMessage());
			return array('success' => false, 'message' => 'An error occurred while fetching pegawai data.');
		}
	}

	function getListHistoryKehadiran($params)
	{
		// die(var_dump($params));
		try {
			// Call the stored procedure and check for result
			$mresult = $this->tp_connpgsql->callSpCount('kehadiran.sp_getlistabsensipeg', $params);

			if (empty($mresult) || !isset($mresult['data'])) {
				throw new Exception('No data returned from stored procedure.');
			}

			$data = array();
			// Check and process each record
			foreach ($mresult['data'] as $r) {
				$r['fileexist'] = false;
				if (!empty($r['files'])) {
					$filePath = config_item('eservices_upload_dok_path') . $r['files'];
					// Use file_exists and is_file in a single condition
					$r['fileexist'] = file_exists($filePath) && is_file($filePath);
				}

				$data[] = $r;
			}

			$result = array(
				'success' => true,
				'count' => $mresult['count'],
				'data' => $data
			);

		} catch (Exception $e) {
			// Error handling in case of exception
			$result = array(
				'success' => false,
				'message' => $e->getMessage()
			);
		}

		return $result;
	}

	function deleteAbsensi($params)
	{
		// die(var_dump($params));
		$this->load->database();
		$this->db->trans_start();

		$this->db->query("SELECT kehadiran.sp_deleteabsensi(?, ?);", $params);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function getInfoPegawai($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('public.sp_getinfopegawai', $params);
		return $mresult;
	}

	function getComboStatusCuti()
	{
		try {
			// Ensure the database is loaded
			if (!isset($this->db)) {
				$this->load->database();
			}

			// Execute the query
			$query = $this->db->get('kehadiran.absensi_status');  // Use CodeIgniter's `get` method

			// Check for results and return as an array
			return $query->num_rows() > 0 ? $query->result_array() : array();

		} catch (Exception $e) {
			// Log any errors
			log_message('error', 'getComboStatusCuti Error: ' . $e->getMessage());
			return array();  // Return an empty array on failure
		}
	}

}
