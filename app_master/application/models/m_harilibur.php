<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class m_harilibur extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function getListHariLibur()
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT id as hariliburid, TO_CHAR(tgl, 'DD/MM/YYYY') tgl, name as keterangan, CASE WHEN isaktif = 0 THEN 2 ELSE isaktif END as status, jenis AS jenisid,
				CASE WHEN jenis = '1' THEN 'Hari Libur Nasional' WHEN jenis = '2' THEN 'Cuti Bersama' ELSE NULL END jenis
			FROM harilibur
			ORDER BY TO_CHAR(tgl, 'YYYY/MM') DESC
		");
		$this->db->close();
		return $q->result_array();
	}

	function tambah($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			INSERT INTO harilibur(id, tgl, name, isaktif, jenis)
			SELECT COALESCE(MAX(id)+1,1), TO_DATE(?, 'DD/MM/YYYY'), ?, ?, ?
			FROM harilibur 
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function ubah($params)
	{
		$status = $params['status'];
		if ($status == '2') {
			$status = '0';
		}

		try {
			// Load the database and start a transaction
			$this->load->database();
			$this->db->trans_start();

			// Use a parameterized query to prevent SQL injection
			$sql = "
            UPDATE harilibur 
            SET tgl = TO_DATE(?, 'DD/MM/YYYY'), 
                name = ?, 
                isaktif = CAST(? AS INT), 
                jenis = CAST(? AS INT) 
            WHERE id = CAST(? AS INT)
        ";

			// Execute the query with the provided parameters
			$this->db->query($sql, array(
				$params['tgl'],
				$params['keterangan'],
				$status,
				$params['jenis'],
				$params['hariliburid']
			));

			// Complete the transaction
			$this->db->trans_complete();

			// Check transaction status
			if ($this->db->trans_status() === FALSE) {
				// Transaction failed, throw an exception
				throw new Exception('Failed to update record');
			}

			// Close the database connection
			$this->db->close();

			// Return true on success
			return true;

		} catch (Exception $e) {
			// Rollback in case of an error
			$this->db->trans_rollback();

			// Log the error (optional)
			log_message('error', 'Error in ubah function: ' . $e->getMessage());

			// Close the database connection
			$this->db->close();

			// Return false on failure
			return false;
		}
	}
	
	function hapus($id)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("DELETE FROM harilibur WHERE id = ?", array($id));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
}