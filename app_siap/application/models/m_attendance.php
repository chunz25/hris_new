<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class m_attendance extends CI_Model
{
	private $CI;

	function __construct()
	{
		parent::__construct();
		$this->CI = &get_instance();
	}

	function getDataAbsensi($params)
	{
		// die(var_dump($params));
		// Validating parameters to ensure data integrity
		if (empty($params["username"]) || empty($params["nik"]) || empty($params["tglmulai"]) || empty($params["tglselesai"])) {
			return $this->errorResponse('Missing parameters');
		}

		$user = $params["username"];
		$nik = $params["nik"];
		$startdate = $params["tglmulai"];
		$enddate = $params["tglselesai"];
		$v_start = $params["v_start"];
		$v_limit = $params["v_limit"];

		$host = 'http://10.101.0.85/attendance/login.php';
		$data = array("username" => $user);

		try {
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $host);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Ensuring proper data encoding
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$response = curl_exec($ch);
			if ($response === false) {
				return $this->errorResponse('CURL Error: ' . curl_error($ch));
			}
			curl_close($ch);

			$return = json_decode($response);

			if ($return === null || !isset($return->accessToken)) {
				return $this->errorResponse('Failed to obtain token.');
			}

			$token = $return->accessToken;

			// Prepare data for the attendance request
			$attendanceData = array(
				'nik' => $nik,
				'startdate' => $startdate,
				'enddate' => $enddate,
				'v_start' => $v_start,
				'v_limit' => round($v_limit/2, 0, PHP_ROUND_HALF_UP),
			);

			// die(var_dump($attendanceData));

			$absen = $this->jwt_request($token, $attendanceData);

			if ($absen === null) {
				return $this->errorResponse('Failed to retrieve attendance data.');
			}

			return $absen;

		} catch (Exception $e) {
			return $this->errorResponse('An error occurred: ' . $e->getMessage());
		}
	}

	function getDataAbsensiStore($params)
	{
		// Validating parameters to ensure data integrity
		if (empty($params["username"]) || empty($params["nik"]) || empty($params["tglmulai"]) || empty($params["tglselesai"])) {
			return $this->errorResponse('Missing parameters');
		}

		$user = $params["username"];
		$nik = $params["nik"];
		$startdate = $params["tglmulai"];
		$enddate = $params["tglselesai"];
		$v_start = $params["v_start"];
		$v_limit = $params["v_limit"];

		$host = 'http://10.101.0.85/attendance/login.php';
		$data = array("username" => $user);

		try {
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $host);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Ensuring proper data encoding
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$response = curl_exec($ch);
			if ($response === false) {
				return $this->errorResponse('CURL Error: ' . curl_error($ch));
			}
			curl_close($ch);

			$return = json_decode($response);

			if ($return === null || !isset($return->accessToken)) {
				return $this->errorResponse('Failed to obtain token.');
			}

			$token = $return->accessToken;

			// Prepare data for the attendance request
			$attendanceData = array(
				'nik' => $nik,
				'startdate' => $startdate,
				'enddate' => $enddate,
				'v_start' => $v_start,
				'v_limit' => round($v_limit/2, 0, PHP_ROUND_HALF_DOWN),
			);

			// die(var_dump($attendanceData));

			$absen = $this->jwt_request_store($token, $attendanceData);

			if ($absen === null) {
				return $this->errorResponse('Failed to retrieve attendance data.');
			}

			return $absen;

		} catch (Exception $e) {
			return $this->errorResponse('An error occurred: ' . $e->getMessage());
		}
	}

	function jwt_request($token, $param)
	{
		$url = 'http://10.101.0.85/attendance/getabsenall.php';
		$ch = curl_init($url);

		$headers = array(
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token
		);

		// Set cURL options
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));

		$result = curl_exec($ch);
		if ($result === false) {
			return $this->errorResponse('CURL Error: ' . curl_error($ch));
		}

		curl_close($ch);

		return json_decode($result, true); // Decode JSON and return as an array
	}

	function jwt_request_store($token, $param)
	{
		$url = 'http://10.101.0.85/attendance/getabsenstore.php';
		$ch = curl_init($url);

		$headers = array(
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token
		);

		// Set cURL options
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));

		$result = curl_exec($ch);
		if ($result === false) {
			return $this->errorResponse('CURL Error: ' . curl_error($ch));
		}

		curl_close($ch);

		return json_decode($result, true); // Decode JSON and return as an array
	}

	private function errorResponse($message)
	{
		http_response_code(401);
		$return = array(
			"status" => false,
			"message" => $message
		);
		echo json_encode($return);
		exit();
	}

	function insertStaging($data)
	{
		$this->CI->load->database();
		$this->CI->db->trans_start();

		// Truncate tables before inserting new data
		$this->CI->db->truncate('staging.stgabsensipegawai');

		// Prepare an array for batch insert
		$batchData = [];

		foreach ($data as $d) {
			// Prepare each row data as an associative array
			$batchData[] = [
				'nik' => $d['nik'],
				'nama' => $d['nama'],
				'hari' => $d['hari'],
				'tgl' => $d['tgl'],
				'scanmasuk' => $d['scanmasuk'],
				'scankeluar' => $d['scankeluar'],
				'pengecualian' => $d['pengecualian'],
				'ket' => $d['ket'],
			];
		}

		// Perform batch insert if there is data
		if (!empty($batchData)) {
			$this->CI->db->insert_batch('staging.stgabsensipegawai', $batchData);
		}

		// Complete the transaction
		$this->CI->db->trans_complete();

		// Close the database connection
		$this->CI->db->close();

		// Return the transaction status (TRUE if successful, FALSE if any error)
		return $this->CI->db->trans_status();
	}

	function getDataAbsen()
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT * FROM staging.stgabsensipegawai
		");
		$this->db->close();
		return $q->result_array();
	}

	function getDataAbsensiAll($params)
	{
		// die(var_dump($params));
		// Validating parameters to ensure data integrity
		if (empty($params["username"]) || empty($params["nik"]) || empty($params["tglmulai"]) || empty($params["tglselesai"])) {
			return $this->errorResponse('Missing parameters');
		}

		$user = $params["username"];
		$nik = $params["nik"];
		$startdate = $params["tglmulai"];
		$enddate = $params["tglselesai"];
		$v_start = $params["v_start"];
		$v_limit = $params["v_limit"];

		$host = 'http://10.101.0.85/attendance/login.php';
		$data = array("username" => $user);

		try {
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $host);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Ensuring proper data encoding
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$response = curl_exec($ch);
			if ($response === false) {
				return $this->errorResponse('CURL Error: ' . curl_error($ch));
			}
			curl_close($ch);

			$return = json_decode($response);

			if ($return === null || !isset($return->accessToken)) {
				return $this->errorResponse('Failed to obtain token.');
			}

			$token = $return->accessToken;

			// Prepare data for the attendance request
			$attendanceData = array(
				'nik' => $nik,
				'startdate' => $startdate,
				'enddate' => $enddate,
				'v_start' => $v_start,
				'v_limit' => $v_limit,
				// 'v_limit' => round($v_limit/2, 0, PHP_ROUND_HALF_UP),
			);

			// die(var_dump($attendanceData));

			$absen = $this->jwt_request_All($token, $attendanceData);

			if ($absen === null) {
				return $this->errorResponse('Failed to retrieve attendance data.');
			}

			return $absen;

		} catch (Exception $e) {
			return $this->errorResponse('An error occurred: ' . $e->getMessage());
		}
	}

	function jwt_request_All($token, $param)
	{
		$url = 'http://10.101.0.85/attendance/getabsenall_new.php';
		$ch = curl_init($url);

		$headers = array(
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token
		);

		// Set cURL options
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param));

		$result = curl_exec($ch);
		if ($result === false) {
			return $this->errorResponse('CURL Error: ' . curl_error($ch));
		}

		curl_close($ch);

		return json_decode($result, true); // Decode JSON and return as an array
	}
}
