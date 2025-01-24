<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class app extends SIAP_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->default_create();
	}

	private function default_create()
	{
		$data['config'] = array(
			"app_name" => config_item('app_name'),
			"app_long_name" => config_item('app_long_name'),
			"url_asset" => config_item('url_asset'),
			"url_css" => config_item('url_css'),
			"url_js" => config_item('url_js'),
			"url_image" => config_item('url_image'),
			"url_ext" => config_item('url_ext'),
			"url_app" => config_item('url_app'),
			"PAGESIZE" => config_item('PAGESIZE'),
			"BASE_URL" => base_url(),
			"SITE_URL" => site_url(),
			"MASTER_URL" => config_item('url_master'),
			'view_siap' => config_item('view_siap'),
			'tombol' => config_item('tombol'),
			'userid' => $this->session->userdata('userid'),
			'username' => $this->session->userdata('username'),
			'nama' => $this->session->userdata('nama'),
			'usergroup' => $this->session->userdata('akses_siap'),
			'no_image_person_url' => config_item('no_image_person_url'),
			'siap_upload_url_foto' => config_item('siap_upload_foto_url'),
			'footer' => config_item('footer')
		);
		$this->load->view('v_app', $data);
	}
}
