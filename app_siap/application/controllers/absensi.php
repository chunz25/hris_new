<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class absensi extends SIAP_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('m_absensi');
		$this->load->model('m_pegawai');
	}

	function getListPegawai(){
		$params = array(
			'v_satkerid' => ifunsetempty($_POST,'satkerid',null),
			'v_lokasiid' => ifunsetempty($_POST,'lokasi',null),
			'v_start' => ifunsetempty($_POST,'start',0),
			'v_limit' => ifunsetempty($_POST,'limit',config_item('PAGESIZE'))			
		);		
		$mresult = $this->m_absensi->getListPegawai($params);
		echo json_encode($mresult);		
	}
	
	function getListAbsensi(){
		// $this->m_pegawai->addlogs();
		$count = ifunsetempty($_POST,'fingerid',null);
		$length = substr_count($count ,",");

		$params = array(
			'v_fingerid' => ifunsetempty($_POST,'fingerid',null),
			'v_tglmulai' => ifunsetempty($_POST,'tglmulai',null),
			'v_tglselesai' => ifunsetempty($_POST,'tglselesai',null),
			'v_lokasiid' => ifunsetempty($_POST,'lokasiid',null),
			'v_finger' => $length + 1,
			'v_start' => ifunsetempty($_POST,'start',0),
			'v_limit' => ifunsetempty($_POST,'limit',config_item('PAGESIZE')),			
		);
		$mresult = $this->m_absensi->getListAbsensi($params);
		echo json_encode($mresult);
	}
	
	function get_import_file(){
		// $this->m_pegawai->addlogs();
		try{
			$template = $_FILES['dokumen']['tmp_name'];
			
			$objPHPExcel = $this->cetak_phpexcel->loadTemplate($template);
			$objWorksheet = $objPHPExcel->setActiveSheetIndexbyName('Sheet1');
			
			$highestRow = $objWorksheet->getHighestRow();
			$highestColumn = $objWorksheet->getHighestColumn();
			$highestColumnIndex = 73;

			$startRow  = 5;
			$startColumn  = 0;
			
			$data = array();			
			$fhead = array();
			
			for($row = $startRow; $row <= $highestRow; ++ $row){				
				$val = array();
				if($row == 5){					
					for($col = $startColumn; $col < $highestColumnIndex; ++ $col){
						$cell = $objWorksheet->getCellByColumnAndRow($col, $row);
						if( !empty($cell->getValue())){
							$fhead[] = $cell->getValue();
						}						
					}					
				}			
				else{
					for($col = $startColumn; $col < sizeof($fhead); ++ $col){					
						$cell = $objWorksheet->getCellByColumnAndRow($col, $row);
						$cell = $cell->getValue();				
						
						// Jika tanggal format excel maka convert ke dalam format Y-m-d
						if($col == 0 and $row > 1){
							$celltgl = $objWorksheet->getCellByColumnAndRow(0, $row);
							$cell = $celltgl->getValue();
							if( !empty($cell)){
								if(PHPExcel_Shared_Date::isDateTime($celltgl)) {
									$cell = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($cell)); 
								}								
							}
						}					
						
						// Jika kosong maka jangan diinsert kedalam database
						// if( !empty($cell)){
							// $val[$fhead[$col]] = $cell;
						// }
						if(strlen($cell) > 0){
							$val[$fhead[$col]] = $cell;
						}
					}
					if(sizeof($val) > 0){
						$data[] = $val;
					}					
				}
			}			
			$result = array('success' => true, 'data' => $data, 'message' => $highestColumn);
			echo json_encode($result);
		}
		catch(Exception $e){
			$result = array('success' => false, 'data' => '', 'message' => 'File upload tidak sesuai dengan template');
			echo json_encode($result);
		}	
	}

	function proses_import_file(){
		// $this->m_pegawai->addlogs();
		$params = array();
		$params = json_decode($this->input->post('params'), true);

		$o=0;
		foreach($params as $key => $val) {
			$temp = array();
			if($key !== 'No.') {
				$temp['nik'] = $params['Nik'];
				$temp['tgl'] = $params['Tanggal'];
				$temp['tgl1'] = !empty($params['1']) ? $params['1'] : null;
				$temp['tgl2'] = !empty($params['2']) ? $params['2'] : null;
				$temp['tgl3'] = !empty($params['3']) ? $params['3'] : null;			
				$temp['tgl4'] = !empty($params['4']) ? $params['4'] : null;
				$temp['tgl5'] = !empty($params['5']) ? $params['5'] : null;
				$temp['tgl6'] = !empty($params['6']) ? $params['6'] : null;
				$temp['tgl7'] = !empty($params['7']) ? $params['7'] : null;
				$temp['tgl8'] = !empty($params['8']) ? $params['8'] : null;
				$temp['tgl9'] = !empty($params['9']) ? $params['9'] : null;
				$temp['tgl10'] = !empty($params['10']) ? $params['10'] : null;
				$temp['tgl11'] = !empty($params['11']) ? $params['11'] : null;
				$temp['tgl12'] = !empty($params['12']) ? $params['12'] : null;
				$temp['tgl13'] = !empty($params['13']) ? $params['13'] : null;
				$temp['tgl14'] = !empty($params['14']) ? $params['14'] : null;
				$temp['tgl15'] = !empty($params['15']) ? $params['15'] : null;
				$temp['tgl16'] = !empty($params['16']) ? $params['16'] : null;
				$temp['tgl17'] = !empty($params['17']) ? $params['17'] : null;
				$temp['tgl18'] = !empty($params['18']) ? $params['18'] : null;
				$temp['tgl19'] = !empty($params['19']) ? $params['19'] : null;
				$temp['tgl20'] = !empty($params['20']) ? $params['20'] : null;
				$temp['tgl21'] = !empty($params['21']) ? $params['21'] : null;
				$temp['tgl22'] = !empty($params['22']) ? $params['22'] : null;
				$temp['tgl23'] = !empty($params['23']) ? $params['23'] : null;
				$temp['tgl24'] = !empty($params['24']) ? $params['24'] : null;
				$temp['tgl25'] = !empty($params['25']) ? $params['25'] : null;
				$temp['tgl26'] = !empty($params['26']) ? $params['26'] : null;
				$temp['tgl27'] = !empty($params['27']) ? $params['27'] : null;
				$temp['tgl28'] = !empty($params['28']) ? $params['28'] : null;
				$temp['tgl29'] = !empty($params['29']) ? $params['29'] : null;
				$temp['tgl30'] = !empty($params['30']) ? $params['30'] : null;
				$temp['tgl31'] = !empty($params['31']) ? $params['31'] : null;

				// check value
				$sendparams = array(
					'v_nik' => $params['Nik'],
					'v_tgl' => $params['Tanggal']

				);
				$mresultSchedule = $this->m_absensi->getSchedule($sendparams);

	            if(count($mresultSchedule) > 0) {
	            	// update when have a same value
	            	$mresult = $this->m_absensi->updateSchedule($temp);
					if($mresult) $o++;
				} else {
					// insert when have a new value
					$mresult = $this->m_absensi->addPengajuanSchedule($temp);
					if($mresult) $o++;
				}
			}
		}		
		if($o > 0){
			$result = array('success' => true, 'message' => 'data berhasil');
		}
		else{
			$result = array('success' => false, 'message' => 'data gagal');
		}
		echo json_encode($result);
	}	
	
	function cetakdokumen(){	
		// $this->m_pegawai->addlogs();
		$params = array(
			'v_tglmulai' => ifunsetemptybase64($_GET,'tglmulai',null),
			'v_tglselesai' => ifunsetemptybase64($_GET,'tglselesai',null),
			'v_fingerid' => ifunsetemptybase64($_GET,'fingerid',null)
		);
		$mresult = $this->m_absensi->getListAbsensiCetak($params);
		$mresultsch = $this->m_absensi->getListSchedule($params);
		$jml = $mresultsch['count'];

		// jika ada schedule cetak schedule
		if( $jml > 0 ) {
			$TBS = $this->template_cetak->createNew('xlsx',config_item("siap_tpl_path")."absensi_karyawan_sch.xlsx");		
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix'])!=='') && ($_SERVER['SERVER_NAME']=='localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header',array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$TBS->MergeBlock('sch', $mresultsch['data']);
			$file_name = str_replace('.','_'.date('Y-m-d').'.',"absensi_karyawan_sch.xlsx");
			$file_name = str_replace('.','_'.$suffix.'.',$file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else {
			$TBS = $this->template_cetak->createNew('xlsx',config_item("siap_tpl_path")."absensi_karyawan.xlsx");		
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix'])!=='') && ($_SERVER['SERVER_NAME']=='localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header',array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.','_'.date('Y-m-d').'.',"absensi_karyawan.xlsx");
			$file_name = str_replace('.','_'.$suffix.'.',$file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		}	
	}

	
}