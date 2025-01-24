<?php 
include_once("../../setting/database.php");
class connectpgsql{
	var $conn_id = FALSE;
	
	function __construct(){
		$this->connect();
	}
	function connect(){
		$hostname = $GLOBALS['db']['default']['hostname'];
		$username = $GLOBALS['db']['default']['username'];
		$password = $GLOBALS['db']['default']['password'];
		$port = $GLOBALS['db']['default']['port'];
		$dbname = $GLOBALS['db']['default']['database'];
		
		$this->conn_id = new PDO("pgsql:host=$hostname;port=$port;dbname=$dbname", $username, $password);
		if($this->conn_id === false){
			die('check your connection or configuration pgsql');
			exit;
		}		
		return $this->conn_id;		
	}
	function callSpReturn($spName, $params, $isShowExecute=false){
		$this->connect();
		$conn_id = $this->conn_id;		
		$v_result = 'r';		
		$convert_string = "(";
		foreach($params as $key=>$value){
			if($value===null){
				$convert_string .= "null,";
			}
			else{
				$convert_string .= "".$this->escape(str_replace("'",'\'\'',$value)).",";
			}
		}
		
		$convert_string .= ":v_result);";		
		$sql = "SELECT $spName $convert_string";
		
		$conn_id->beginTransaction();					
		$stmt = $conn_id->prepare($sql);
		$stmt->bindParam('v_result', $v_result, PDO::PARAM_STR);
		$stmt->execute();
		
		$stmt = $conn_id->query('FETCH ALL FROM "r"');
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt->closeCursor();		
		$conn_id->commit();			
		
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
	function escape($str){
		if(is_string($str)){
			$str = "'".$this->escape_str($str)."'";
		}
		elseif(is_bool($str)){
			$str = ($str === FALSE) ? 0 : 1;
		}
		elseif(is_null($str)){
			$str = 'NULL';
		}
		return $str;
	}	
	function escape_str($str, $like = FALSE){
		return str_replace("'", "''", $str);
	}		
}
?>