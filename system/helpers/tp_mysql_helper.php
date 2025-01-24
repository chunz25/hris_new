<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function call_sp_count($name_sp, $data=array(), $outcount='count', $outdata='data'){
	$CI = &get_instance();
	$render_string_params = "";
	$conn = new mysqli($CI->db->hostname, $CI->db->username, $CI->db->password, $CI->db->database, $CI->db->port);
	foreach($data as $key => $value){
		if($value == null){
			$render_string_params .= "'$value',";
		}				
		else{
			$render_string_params .= "".$CI->db->escape(str_replace("'",'\'\'',$value)).",";
		}				
	}
	
	$render_string_params.="@rowcount);select @rowcount;";	
	
	$sql = "CALL $name_sp ($render_string_params";
	// echo $sql;
	// exit;
	
	$query = $conn->multi_query($sql);
	$result = $conn->store_result();		
	$arr = array();		
	if($query){
		while($row = $result->fetch_assoc()){
			$arr[] = $row;
		}
		$result->free();
		$conn->next_result();		
		$conn->next_result();
		$result = $conn->store_result();
		$count = $result->fetch_row();
		$count = (int) $count[0];
		$conn->close();
		$result = array('success'=>true, $outcount=>$count, $outdata=>$arr);
		return $result;			
	}
	else{
		return false;
	}
}






