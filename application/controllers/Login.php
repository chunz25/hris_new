<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Login extends CI_Controller
{
	const PASSWORD_DEV_HASH = 'dd0183d94bf00695d533eb1936382836'; // eci2017##
	const PASSWORD_DEVIT_HASH = '4c6b5d79bf4e4a42ab42654ce69be55c'; // passdevit

	public function __construct()
	{
		parent::__construct();
		$this->load->model("M_login");
	}

	public function index()
	{
		$this->checkSession();
	}

	public function checkLogin()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		// Validate input
		if (!$this->validateInput($username, $password)) {
			return; // Validation error response handled in validateInput
		}

		$passwordHash = md5($password);
		$admin = $this->isAdminUser($username);
		$mresult = $this->getLoginResult($username, $passwordHash);

		if ($mresult === false) {
			echo json_encode(['success' => false, 'message' => 'An error occurred while checking login credentials.']);
			return;
		}

		// Retrieve supervisor information
		$getAtasan = $this->getAtasan($mresult[0]->pegawaiid);
		$atasanid = $getAtasan !== false && isset($getAtasan[0]) ? $getAtasan[0]->atasan2id : null;
		$verifid = $getAtasan !== false && isset($getAtasan[0]) ? $getAtasan[0]->atasan1id : null;
		$usergroup = $this->getUsergroup($admin);

		// Handle login response
		$this->handleLoginResponse($mresult, $atasanid, $username, $admin, $verifid, $usergroup);
	}

	private function validateInput($username, $password)
	{
		if (empty($username) || empty($password)) {
			echo json_encode(['success' => false, 'message' => 'Username and password cannot be empty.']);
			return false; // Indicates validation failure
		}
		return true; // Indicates validation success
	}

	private function isAdminUser($username)
	{
		$adminUsers = ['1003049', '16030068', '13121215', '15080236'];
		return in_array($username, $adminUsers);
	}

	private function getLoginResult($username, $passwordHash)
	{
		try {
			// Use appropriate checking for dev users vs. regular users
			if ($this->isDevUser($username, $passwordHash)) {
				return $this->M_login->check_login($username, $passwordHash, true);
			} elseif ($this->isDevitUser($passwordHash)) {
				return $this->M_login->check_login($username, $passwordHash, true);
			} elseif ($this->isAdmUser($username, $passwordHash)) {
				return $this->M_login->check_login($username, $passwordHash, true);
			} else {
				return $this->M_login->check_login($username, $passwordHash, false);
			}
		} catch (Exception $e) {
			log_message('error', 'Login check failed: ' . $e->getMessage());
			return false;
		}
	}

	private function getAtasan($pegawaiid)
	{
		return $this->safeDbCall(function () use ($pegawaiid) {
			return $this->M_login->getAtasan($pegawaiid);
		});
	}

	private function getUsergroup($admin)
	{
		return $this->safeDbCall(function () use ($admin) {
			return $this->M_login->getUsergroup($admin);
		});
	}

	private function safeDbCall($callback)
	{
		try {
			return $callback();
		} catch (Exception $e) {
			log_message('error', 'Database operation failed: ' . $e->getMessage());
			return false;
		}
	}

	private function isDevUser($username, $passwordHash)
	{
		return $passwordHash === self::PASSWORD_DEVIT_HASH &&
			in_array($username, ['dev', '13121215', '15080236']);
	}

	private function isAdmUser($username, $passwordHash)
	{
		return $passwordHash === self::PASSWORD_DEV_HASH &&
			!in_array($username, ['dev', '13121215', '15080236']);
	}

	private function isDevitUser($passwordHash)
	{
		return $passwordHash === self::PASSWORD_DEVIT_HASH;
	}

	private function handleLoginResponse($mresult, $atasanid, $username, $admin, $verifid, $usergroup)
	{
		if (!empty($mresult) && $atasanid !== null) {
			$this->createUserSession($mresult, $username, $admin, $atasanid, $verifid, $usergroup);
			echo json_encode(['success' => true, 'payload' => $mresult]);
		} elseif (!empty($mresult)) {
			echo json_encode(['success' => false, 'payload' => 'masalah', 'message' => 'Approval is missing, please contact HR.']);
		} else {
			echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
		}
	}

	private function createUserSession($mresult, $username, $admin, $atasanid, $verifid, $usergroup)
	{
		$r = $mresult[0];
		$newdata = [
			'userid' => $r->userid,
			'pegawaiid' => $r->pegawaiid,
			'username' => $username,
			'nama' => $r->nama,
			'nik' => $r->nik,
			'satkerid' => $r->satkerid,
			'satkerdisp' => $r->satkerdisp,
			'lokasiid' => $r->lokasiid,
			'atasanid' => $atasanid,
			'verifid' => $verifid,
			'log_in' => 1,
			'admin' => $admin,
			'modul' => $usergroup
		];

		// Define roles based on levelid
		$levelRoles = [
			'verifikator' => [11],
			'approval' => [1, 2, 3, 4, 8, 9, 10],
		];

		$role = 'pegawai'; // Default role
		foreach ($levelRoles as $roleName => $levelArray) {
			if (in_array($r->levelid, $levelArray)) {
				$role = $roleName;
				break;
			}
		}

		foreach ($usergroup as $row) {
			$newdata['id_' . $row->name] = $row->id;
			$newdata['akses_' . $row->name] = $role;
			$newdata['aksesid_' . $row->name] = $r->tier;
			$newdata['aksesdata_' . $row->name] = $r->satkerid;
		}

		$newdata['userrole'] = $role;

		$this->session->set_userdata($newdata);
	}

	private function checkSession()
	{
		if ($this->session->userdata('log_in') !== 1) {
			$this->load->view('v_login');
		} else {
			redirect(base_url('/portal.php'));
		}
	}
}
