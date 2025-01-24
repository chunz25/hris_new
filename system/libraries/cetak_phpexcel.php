<?php
require_once('PHPExcel.php');
require_once 'PHPExcel/IOFactory.php';
class cetak_phpexcel{
	function __construct($type=""){}
	function createNew(){
		$objPHPExcel = new PHPExcel();
		return $objPHPExcel;		
	}
	function loadTemplate($str){
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objPHPExcel = $objReader->load($str);
		return $objPHPExcel;
	}
	function createHeader($sheet,$base,$i,$colomStart,$ln,$value,$center,$bg){ 
		$ln=$colomStart+$ln-1;
		$A=$this->getNameFromNumber($colomStart);
		$B=$this->getNameFromNumber($ln);
		$sheet->setCellValue($A . $base, $value);
		$colomStart=$ln+1;
		
		$sheet->setCellValue($A . $base, $value);
		
		$sheet->getStyle("$A$base:$B$base")->getAlignment()->setWrapText(true);
		
		if($A!=$B){
			$sheet->mergeCells("$A$base:$B".($base+$i));
			$sheet->getStyle("$A$base:$B".($base+$i))->getAlignment()->setWrapText(true);
		}
		else{
			$sheet->mergeCells("$A$base:$A".($base+$i));
			$sheet->getStyle("$A$base:$A".($base+$i))->getAlignment()->setWrapText(true);		
		}
		
		$this->cellColor($sheet,"$A$base:$B$base", $bg);
		
		if($center===FALSE){
			$sheet->getStyle("$A$base:$B$base")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			$sheet->getStyle("$A$base:$B$base")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		}else{
			$sheet->getStyle("$A$base:$B$base")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheet->getStyle("$A$base:$B$base")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}
		$phpFont = new PHPExcel_Style_Font();
		$phpFont->setBold(true);
		$phpFont->setName('Calibri');
		$phpFont->setSize(10); 

		$sheet->getStyle("$A$base:$B$base")->setFont($phpFont);	
		
		return $colomStart;
	}	
	function getNameFromNumber($num) {
		$numeric = ($num - 1) % 26;
		$letter = chr(65 + $numeric);
		$num2 = intval(($num - 1) / 26);
		if ($num2 > 0) {
			return $this->getNameFromNumber($num2) . $letter;
		} else {
			return $letter;
		}
	}	
	function cellColor($sheet,$cells,$color){
		$sheet->getStyle($cells)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
			'startcolor' => array('rgb' => $color)
		));
	}	
}
?>
