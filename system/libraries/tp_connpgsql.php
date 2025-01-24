<?php
class tp_connpgsql{
	var $CI;
	var $conn_id = FALSE;
	var $result_id = FALSE;
	var $bind_marker = '?';
	
	function __construct(){
		$this->CI =&get_instance();
		$this->connect();
	}
	
	function connect(){
		$hostname = $this->CI->db->hostname;
		$username = $this->CI->db->username;
		$password = $this->CI->db->password;
		$port = $this->CI->db->port;
		$dbname = $this->CI->db->database;
		
		$this->conn_id = new PDO("pgsql:host=$hostname;port=$port;dbname=$dbname", $username, $password);
		if($this->conn_id === false){
			die('check your connection or configuration pgsql');
			exit;
		}
		return $this->conn_id;
	}
	function callSpCount($spName, $params, $isShowExecute=false){
		$v_count = 'c';
		$v_result = 'r';		
		$convert_string = "(";
		foreach($params as $key=>$value){			
			if(is_null($value)){
				$convert_string .= "null,";
			}
			else{
				$convert_string .= "".$this->CI->db->escape(str_replace("'",'\'\'',$value)).",";
			}
		}
		
		$convert_string .= ":v_count, :v_result);";
		$sql = "SELECT $spName $convert_string";
		
		if($isShowExecute){
			$printexecute = 'BEGIN; ' . $sql . ' FETCH FIRST FROM c; FETCH ALL FROM r; COMMIT;';
			echo $printexecute;
			exit;			
		}
		
		$this->conn_id->beginTransaction();				
		$stmt = $this->conn_id->prepare($sql);
		$stmt->bindParam('v_count', $v_count, PDO::PARAM_STR);
		$stmt->bindParam('v_result', $v_result, PDO::PARAM_STR);
		$stmt->execute();
				
		$stmt = $this->conn_id->query('FETCH ALL FROM "r"');
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt->closeCursor();
		
		$stmt2 = $this->conn_id->query('FETCH FIRST FROM "c"');
		$fcount = $stmt2->fetch(PDO::FETCH_ASSOC);
		$stmt2->closeCursor();
		
		$this->conn_id->commit();
		
		$result = array();
		if($stmt && $stmt2){
			$result = array('count' => $fcount['count'], 'data' => $results);
			return $result;	
		}
		else{
			return false;
		}						
	}	
	function callSpReturn($spName, $params, $isShowExecute=false){
		$v_result = 'r';		
		$convert_string = "(";
		foreach($params as $key=>$value){
			if($value===null){
				$convert_string .= "null,";
			}
			else{
				$convert_string .= "".$this->CI->db->escape(str_replace("'",'\'\'',$value)).",";
			}
		}
		
		$convert_string .= ":v_result);";
		$sql = "SELECT $spName $convert_string";
		
		// echo $sql;
		// exit;
		
		$this->conn_id->beginTransaction();					
		$stmt = $this->conn_id->prepare($sql);
		$stmt->bindParam('v_result', $v_result, PDO::PARAM_STR);
		$stmt->execute();
		
		$stmt = $this->conn_id->query('FETCH ALL FROM "r"');
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt->closeCursor();		
		$this->conn_id->commit();			
				
		if($stmt){
			$data = array(); $firstrow =array(); $result = array();
			$i=0;
			foreach($results as $r){
				$i++;
				$r['no']=$i;
				if($i==1){
					$firstrow = $r;
				}				
				$data[] = $r;
			}
			
			$result = array('data' => $data, 'firstrow' => $firstrow, 'numrows' => $i);
			return $result;
		}
		else{
			return false;
		}										
	}
}
?>
