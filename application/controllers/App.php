<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class App extends Web_Controller
{
	public function __construct()
	{
		parent::__construct(); // Correct way to call the parent constructor
	}

	public function index()
	{
		if (!$this->session->userdata('log_in')) {
			$this->view_login();
		} else {
			redirect(base_url('/portal.php'));
		}
	}

	private function view_login()
	{
		$content = "v_login";
		$data = array();
		$this->load->view($content, $data);
	}
}
