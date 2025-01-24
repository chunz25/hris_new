<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class image_proses{
	private $CI; 
	private $max_size = '5000';
	private $max_width = '5000';
	private $max_height = '5000';
	
	private $limit_medium = 290;
	private $limit_small = 155;
	private $limit_thumb = 54;
	
	function __construct(){
		$this->CI =& get_instance();
		$this->CI->load->library('image_lib') ;
    }

    function create_image($filename="FOTO"){
		$name_file = $_FILES[$filename]['name'];
		if(empty($name_file)){
			return false;
			exit;
		}
		$exp_filename = explode('.',$name_file);
		$filerand = $exp_filename[0] . time().'.'.$exp_filename[1];
		
		
		$config['upload_path']	= $this->CI->config->item('upload_dir_foto_real');
        $config['allowed_types']= $this->CI->config->item('permission_image');
		$config['file_name']	= $filerand;
        $config['max_size']     = $this->max_size;
        $config['max_width']  	= $this->max_width;
        $config['max_height']  	= $this->max_height;
		
		$this->CI->load->library('upload', $config);
		if($this->CI->upload->do_upload($filename)){
		
			$data = $this->CI->upload->data();
			
			$source             = $this->CI->config->item('upload_dir_foto_real').$data['file_name'];
			$destination_thumb	= $this->CI->config->item('upload_dir_foto_thumb');
			$destination_medium	= $this->CI->config->item('upload_dir_foto_medium');
			$destination_small	= $this->CI->config->item('upload_dir_foto_small');
						
			chmod($source, 0777) ;
			
			// crop image medium
            $limit_medium   = $this->limit_medium;
            $limit_small	= $this->limit_small;
            $limit_thumb    = $this->limit_thumb;
			
            $limit_use  = $data['image_width'] > $data['image_height'] ? $data['image_width'] : $data['image_height'] ;
			
            if ($limit_use > $limit_medium || $limit_use > $limit_thumb) {
                $percent_medium = $limit_medium/$limit_use;
                $percent_thumb  = $limit_thumb/$limit_use;
				$percent_small = $limit_small/$limit_use;
            }
			
			$this->image_crop($limit_use, $this->limit_thumb, $data['image_width'], $data['image_height'], $percent_thumb, $source, $destination_thumb, '-thumb');
			$this->image_crop($limit_use, $this->limit_medium, $data['image_width'], $data['image_height'], $percent_medium, $source, $destination_medium, '-medium');
			$this->image_crop($limit_use, $this->limit_small, $data['image_width'], $data['image_height'], $percent_small, $source, $destination_small, '-small');
			
			return $data;
		}
		else{
			return false;
		}		
    }
	function image_crop($limit_use, $limit_img, $data_image_width, $data_image_height, $percent_img, $source, $destination, $name){
		$img['width']  = $limit_use > $limit_img ?  $data_image_width * $percent_img : $data_image_width;
		$img['height'] = $limit_use > $limit_img ?  $data_image_height * $percent_img : $data_image_height;

		$img['image_library'] = 'GD2';
		$img['create_thumb']  = TRUE;
		$img['maintain_ratio']= TRUE;
		
		$img['thumb_marker'] = $name;
		$img['quality']      = '100%' ;
		$img['source_image'] = $source ;
		$img['new_image']    = $destination;
		
		$this->CI->image_lib->initialize($img);
		$this->CI->image_lib->resize();
		$this->CI->image_lib->clear() ;		
	}
	function hapus_image($filename){
		$imagePath = $this->CI->config->item('upload_dir_foto_real').$filename;
		if(file_exists($imagePath) && is_file($imagePath)){						
			$img_temp = explode('.',$filename);
			unlink($this->CI->config->item('upload_dir_foto_real') . $filename);
			unlink($this->CI->config->item('upload_dir_foto_thumb') . $img_temp[0] . '-thumb' . '.' . $img_temp[1]);
			unlink($this->CI->config->item('upload_dir_foto_medium') . $img_temp[0] . '-medium' . '.' . $img_temp[1]);
			unlink($this->CI->config->item('upload_dir_foto_small') . $img_temp[0] . '-small' . '.' . $img_temp[1]);		
			
			return true;
		}
		else{
			return false;
		}		
	}
}