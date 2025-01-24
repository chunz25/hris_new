<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Logout extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("M_login"); // Load the model in the constructor
	}

	public function index()
	{
		// Log the logout action
		// $this->M_login->addlogs();

		// Destroy the session
		$this->session->sess_destroy();

		// Redirect to the login page
		redirect('login');
	}
}
