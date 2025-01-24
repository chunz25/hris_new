<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include  FCPATH2."setting/autoload.php";

$modul = 'eservices';

$autoload['packages'] 	= $auto[$modul]['packages'];
$autoload['libraries'] 	= $auto[$modul]['libraries'];
$autoload['helper'] 	= $auto[$modul]['helper'];
$autoload['config'] 	= $auto[$modul]['config'];
$autoload['language'] 	= $auto[$modul]['language'];
$autoload['model'] 		= $auto[$modul]['model'];

/* End of file autoload.php */
/* Location: ./application/config/autoload.php */