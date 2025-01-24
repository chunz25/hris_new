<?php
$config['base_url'] = "http://" . $_SERVER['HTTP_HOST'] . preg_replace('@/+$@', '', dirname($_SERVER['SCRIPT_NAME']) == "\\" ? "" : dirname($_SERVER['SCRIPT_NAME'])) . '/';

$config['app_name']	= "HRIS";
$config['app_long_name'] = "Human Resource Information System";
$config['instansi'] = "ECI";
$config['instansi_long_name'] = "Electronic City Indonesia";
$config['instansi_address'] = "Jakarta";
$config['footer'] = "&copy;" . date('Y') . ' ' . $config['instansi_long_name'] . ". All Rights reserved. ";
$config['header'] = 'Selamat Datang di Aplikasi ' . $config['app_name'];
$config['PAGESIZE'] = 25;

$config['client_folder_name'] = "eci";
$config['folder_upload'] = "uploads";
$config['folder_thumb'] = "thumbnail";
$config['folder_small'] = "small";
$config['folder_medium'] = "medium";
$config['folder_real'] = "real";
$config['folder_head'] = "header";


// =====================================
// Setting URL Assets
// =====================================
$config['url_asset'] 			= $config['base_url'] . 'media/asset/';
$config['url_css'] 				= $config['url_asset'] . 'css/';
$config['url_js']  				= $config['url_asset'] . 'js/';
$config['url_image'] 			= $config['url_asset'] . 'images/';
$config['url_ext']  			= $config['url_asset'] . 'ext_4/';
$config['url_template']  		= $config['url_asset'] . 'templates/';


$config['client_url'] 		= $config['base_url'] . "setting/" . $config['client_folder_name'] . "/";
$config['client_path'] 		= FCPATH2 . "setting/" . $config['client_folder_name'] . "/";
$config['upload_path'] 		= $config['client_path'] . "uploads/";
$config['upload_url'] 		= $config['client_url'] . "uploads/";
$config['tpl_url'] 			= $config['client_url'] . 'templates/';
$config['tpl_path'] 		= $config['client_path'] . "templates/";
$config['sess_cookie_name'] = 'hcm2017';

$config['upload_dir_foto_thumb'] = FCPATH2 . $config['folder_upload'] . "/foto/" . $config['folder_thumb'] . "/";
$config['upload_dir_foto_small'] = FCPATH2 . $config['folder_upload'] . "/foto/" . $config['folder_small'] . "/";
$config['upload_dir_foto_medium'] = FCPATH2 . $config['folder_upload'] . "/foto/" . $config['folder_medium'] . "/";
$config['upload_dir_foto_real'] = FCPATH2 . $config['folder_upload'] . "/foto/" . $config['folder_real'] . "/";
$config['upload_dir_foto_head'] = FCPATH2 . $config['folder_upload'] . "/foto/" . $config['folder_head'] . "/";

$config['no_image_person_url'] = $config['client_url'] . 'no_image.jpg';
$config['no_image_small'] = $config['client_url'] . 'no_image_small.jpg';
$config['no_image_medium'] = $config['client_url'] . 'no_image_medium.jpg';
$config['no_image_thumbnail'] = $config['client_url'] . 'no_image_thumbnail.jpg';
$config['no_image_head'] = $config['client_url'] . 'no_image_head.png';
$config['dokumen_dir_foto_thumb'] = $config['base_url'] . 'uploads/foto/' . $config['folder_thumb'] . '/';
$config['dokumen_dir_foto_small'] = $config['base_url'] . 'uploads/foto/' . $config['folder_small'] . '/';
$config['dokumen_dir_foto_medium'] = $config['base_url'] . 'uploads/foto/' . $config['folder_medium'] . '/';
$config['dokumen_dir_foto_real'] = $config['base_url'] . 'uploads/foto/' . $config['folder_real'] . '/';
$config['dokumen_dir_foto_head'] = $config['base_url'] . 'uploads/foto/' . $config['folder_head'] . '/';

$config['tombol'] 			= $config['url_image'] . 'icon/';

/* Config Email */
$config['email_protocol'] = "smtp";
$config['email_smtp_host'] = "mail.electronic-city.co.id";
$config['email_smtp_user'] = "dwh@electronic-city-internal.co.id";
$config['email_smtp_pass'] = "ellecc1ty";
$config['email_smtp_port'] = "25";
$config['email_mailtype'] = "html";


$cfg_modul['index']			=	array('0', 'index', 'Sistem Informasi Manajemmen SDM');
$cfg_modul['absensi']		=	array('10', 'absensi', 'Datang Telat / Pulang Cepat');
$cfg_modul['dailyreport']	=	array('98', 'dailyreport', 'Daily Report');
$cfg_modul['eservices']		=	array('2', 'eservices', 'Modul Cuti');
$cfg_modul['siap']			=	array('1', 'siap', 'Sistem Administrasi Pegawai');
$cfg_modul['contract']		=	array('101', 'wfh', 'Work From Home');
$cfg_modul['portal']		=	array('100', 'portal', 'Portal Module');
$cfg_modul['master']		=	array('900', 'master', 'Master Modul');
$cfg_modul['policies']		=	array('4', 'policies', 'Policies');
$cfg_modul['dinas']			=	array('3', 'dinas', 'Perjalanan Dinas');
$cfg_modul['personaldata']	=	array('0', 'personaldata', 'Personal Data');

$data = array();
foreach ($cfg_modul as $row) {
	$modul = $row[1];
	$nama_var = 'config_' . $modul;

	$temp['modulid']					=	$row[0];
	$temp['modul_name']					=	$modul;
	$temp['modul_long_name']			=	$row[2];
	$config['url_' . $modul]				=	$config['base_url'] . $modul . '.php';
	$temp['view_' . $modul]				=	$config['base_url'] . 'app_' . $modul . '/' . $modul . '/';
	$temp['component_' . $modul]			=	$config['base_url'] . 'app_' . $modul . '/' . $modul . '/component/';
	$temp[$modul . '_upload_foto_path']	=	$config['upload_path'] . $modul . '/foto/';
	$temp[$modul . '_upload_dok_path']	=	$config['upload_path'] . $modul . '/dokumen/';
	$temp[$modul . '_upload_foto_url']	=	$config['upload_url'] . $modul . '/foto/';
	$temp[$modul . '_upload_dok_url']		=	$config['upload_url'] . $modul . '/dokumen/';
	$temp[$modul . '_tpl_url']			=	$config['tpl_url'] . $modul . '/';
	$temp[$modul . '_tpl_path']			=	$config['tpl_path'] . $modul . '/';

	$temp['index_page'] 				= $modul . '.php';
	$temp['subclass_prefix'] 			= $modul . '_';

	$$nama_var = $temp;
	$data[] = $temp;
}

$config['url_logout'] = $config['url_index'] . '/logout';
