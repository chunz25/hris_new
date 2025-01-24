<?php
require __DIR__ . '/connectpgsql.php';

class generateCutiBersama extends connectpgsql {
	var $currentYears = "2018";
	
	function __construct(){}
	
	function getCutiBersama($pegawaiid) {
		$conn = $this->connect();		
		
		$sql = "select to_char(tgl, 'DD/MM/YYYY') tgl from eservices.harilibur where jenis = '2' and extract(year from tgl) = '".$this->currentYears."'";
		$q = $conn->query($sql);
		
		foreach($q->fetchAll(PDO::FETCH_ASSOC) as $r) {						
			$mresult = $this->addPengajuanCuti($pegawaiid, $r['tgl']);			
			if($mresult) {
				echo "=== Date " . $r['tgl'] . " : " . "Has been success" . "\n";
			}
			else {
				echo "=== Date " . $r['tgl'] . " : " . "Has been failure" . "\n";
			}			
		}
	}
	
	function getListPegawai() {
		$conn = $this->connect();
		
		$del = $this->delCutiBersama();
		
		if($del) {
			$sql = "select pegawaiid from pegawai where statuspegawaiid = '1'";
			$q = $conn->query($sql);
			
			echo "=================================================\n";
			$data = array();
			$no=1;
			foreach($q->fetchAll(PDO::FETCH_ASSOC) as $r) {
				$temp = array();			
				$temp['pegawaiid'] = $r['pegawaiid'];
				// $temp['cutibersama'] = $this->getCutiBersama($r['pegawaiid']);
				
				echo "Result " . $no . " : " . $r['pegawaiid'] . "\n";
				
				$mresult = $this->getCutiBersama($r['pegawaiid']);
				
				$data[] = $temp;
				$no++;
			}		
			echo "\n=================================================\n";			
		}		
	}
	
	function addPengajuanCuti($pegawaiid, $tglcuti) {
		$sendParams = array(
			'v_pegawaiid' => $pegawaiid,
			'v_periode' => $this->currentYears,
			'v_tglpermohonan' => $tglcuti,
			'v_atasan1' => null,
			'v_atasan2' => null,
			'v_pelimpahan' => null,
			'v_status' => '7',
			'v_verifikasinotes' => null,
			'v_files' => null,
			'v_filestype' => null,
			'v_hp' => null,
		);
		
		$mresult = $this->callSpReturn('eservices.sp_addpengajuancuti', $sendParams);
		$mresult = $mresult['firstrow'];		
		
		if($mresult) {
			$mresult2 = $this->addDetailPengajuanCuti($mresult['pengajuanid'],$tglcuti);
			
			return true;
		}
		else {
			return false;
		}
	}
	
	function addDetailPengajuanCuti($pengajuanid, $tglcuti) {
		$conn = $this->connect();
		
		$params = array(
			'v_pengajuanid' => $pengajuanid,
			'v_jeniscutiid' => '6',
			'v_detailjeniscutiid' => '18',
			'v_tglmulai' => $tglcuti,
			'v_tglselesai' => $tglcuti,
			'v_lama' => '1',
			'v_satuan' => 'HARI KERJA',
			'v_sisacuti' => null,
			'v_alasan' => null,
		);
		
		$sql = "SELECT eservices.sp_adddetailpengajuancuti('".$params['v_pengajuanid']."','".$params['v_jeniscutiid']."','".$params['v_detailjeniscutiid']."','".$params['v_tglmulai']."','".$params['v_tglselesai']."','".$params['v_lama']."','".$params['v_satuan']."',null,null)";		
		$stmt = $conn->prepare($sql);		
		$q = $stmt->execute();
		return $q;
	}
	
	function delCutiBersama() {
		$conn = $this->connect();
		
		$conn->beginTransaction();
		
		$sql = "
			delete from eservices.pengajuancuti where pengajuanid in (
				select pengajuanid
				from eservices.detailpengajuancuti 
				where jeniscutiid = '6' and extract(year from tglmulai) = '".$this->currentYears."'
			)
		";		
		$stmt = $conn->prepare($sql);
		$q = $stmt->execute();
		
		$sql2 = "
			delete from eservices.detailpengajuancuti where jeniscutiid = '6' and extract(year from tglmulai) = '".$this->currentYears."'
		";
		$stmt2 = $conn->prepare($sql2);
		$q2 = $stmt2->execute();
		
		$conn->commit();	

		return true;

	}
		
}

$obj = new generateCutiBersama();
$obj->getListPegawai();

?>