<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_login extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database(); // Load the database
	}

	public function check_login($username, $password, $passing)
	{
		// Start by selecting relevant fields
		$this->db->select('
            u.id AS userid,
            p.id AS pegawaiid,
            u.nik,
            np.fullname AS nama,
            sp.satkerid,
            st.code as satkerdisp,
            d.name AS satkername,
            d.tier AS satkertier,
            d.fk AS satkeratasan,
            l.id AS lokasiid,
            l.code AS lokasicode,
            l.name AS lokasiname,
            sp.levelid,
            sp.jabatanid
        ')
			->from('users u')
			->join('pegawai p', 'p.userid = u.id', 'left')
			->join('namapegawai np', 'p.id = np.pegawaiid', 'left')
			->join('struktur.satkerpegawai sp', 'p.id = sp.pegawaiid AND sp.nourut = 1', 'left')
			->join('struktur.department d', 'sp.satkerid = d.id', 'left')
			->join('struktur.vw_satkertree st', 'd.id = st.id', 'left')
			->join('riwayatjabatan rj', 'p.id = rj.pegawaiid', 'left')
			->join('lokasi l', 'rj.lokasiid = l.id', 'left')
			->join('struktur.jabatan j', 'sp.jabatanid = j.id', 'left')
			->join('struktur.levelgrade lg', 'sp.levelid = lg.id', 'left')
			->where('p.isaktif', 1) // Adding the WHERE clause for active employees
			->where('u.nik', $username);

		// Use password hashing for security
		if (!$passing) {
			$this->db->where('u.password', $password); // Assuming passwords are stored in MD5
		}

		// Execute the query and return results
		return $this->handleQuery($this->db->get());
	}

	public function getAtasan($pegawaiid)
	{
		// Validate the input
		if (empty($pegawaiid)) {
			log_message('error', 'getAtasan called with empty pegawaiid');
			return [];
		}

		// Use Query Builder for distinct query
		$this->db->distinct()
			->select('
                a.id as pegawaiid ,
				b1.manager_id as atasan1id,
				b2.manager_id as atasan2id
            ')
			->from('pegawai a')
			->join('struktur.vw_verifer b1', 'b1.pegawaiid = a.id', 'left')
			->join('struktur.vw_approver b2', 'b2.pegawaiid = a.id', 'left')
			->where(['a.id' => $pegawaiid]);

		// Execute and log the SQL query
		return $this->handleQuery($this->db->get());
	}

	public function getUsergroup($admin)
	{
		try {
			// Define the user groups based on the admin status
			$groupIds = $admin ? [1, 2, 4, 5] : [2, 4, 5];

			// Sanitize the group IDs to ensure they are all integers
			$groupIds = array_map('intval', $groupIds);

			// Convert the array of group IDs into a comma-separated string for the raw SQL query
			$groupIdsStr = implode(',', $groupIds);

			// Write the optimized raw SQL query with CASE WHEN logic
			$sql = "
            SELECT * 
            FROM modul 
            WHERE id IN ($groupIdsStr)
            ORDER BY 
                CASE 
                    WHEN id = 5 THEN 1
                    WHEN id = 1 THEN 5
                    ELSE id 
                END ASC
        ";

			// Execute the query
			$result = $this->db->query($sql);

			// Check if the query was successful
			if ($result === false) {
				// Handle query failure
				throw new Exception("Database query failed.");
			}

			// Return the result (success)
			return $this->handleQuery($result);
		} catch (Exception $e) {
			// Log the error if a logging mechanism exists
			log_message('error', 'Error in getUsergroup: ' . $e->getMessage());

			// Optionally, return an error message or false
			return ['error' => 'An error occurred while fetching user groups.'];
		}
	}

	public function add_logs()
	{
		// Retrieve the last accessed controller and method
		$controllerName = $this->router->fetch_class(); // Gets the current controller name
		$methodName = $this->router->fetch_method();    // Gets the current method name

		// Prepare parameters for insertion
		$params = [
			'user_id' => $this->session->userdata('pegawaiid'),
			'access_url' => $this->input->server('REQUEST_URI'), // Full URI accessed
			'access_func' => $controllerName . '/' . $methodName, // Controller/Method as the last accessed function
			'client_ip' => $this->input->ip_address(),
			'timestamp' => date('Y-m-d H:i:s') // Log the timestamp
		];

		// Insert into user logs with error handling
		try {
			// Attempt to insert the log entry into the database
			$this->db->insert('report.activity_logs', $params);

			// Return true if the insert was successful
			return $this->db->affected_rows() > 0;
		} catch (Exception $e) {
			// Log the error message for debugging
			log_message('error', 'Failed to add log entry: ' . $e->getMessage());
			return false; // Return false on error
		}
	}

	private function handleQuery($query)
	{
		if (!$query) {
			// Log the error if query fails
			log_message('error', 'Database error: ' . json_encode($this->db->error()));
			return []; // Return an empty array in case of an error
		}

		// Log the executed SQL query for debugging
		log_message('debug', 'Executed Query: ' . $this->db->last_query());

		// Return the result as an array of objects, or an empty array if no results found
		return $query->result() ?: [];
	}
}
