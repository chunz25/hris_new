<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function formatDate($tgl, $separator, $neworder, $newseparator = null) {
	if (!$newseparator) {
		$newseparator = $separator;
	}
	
	$split = explode($separator, $tgl);
	$newtgl = $split[$neworder[0]] . $newseparator . $split[$neworder[1]] . $newseparator . $split[$neworder[2]];
	return $newtgl;
}

function dmy2ymd($input, $separator = '-') {
	if ($input) {
		return formatDate($input, $separator, array(2, 1, 0));
	}
	else {
		return $input;
	}
}

function get_textual_month($month_number)

{

  switch($month_number)

  {

    case 1: $month = 'Januari'; break;

    case 2: $month = 'Februari'; break;

    case 3: $month = 'Maret'; break;

    case 4: $month = 'April'; break;

    case 5: $month = 'Mei'; break;

    case 6: $month = 'Juni'; break;

    case 7: $month = 'Juli'; break;

    case 8: $month = 'Agustus'; break;

    case 9: $month = 'September'; break;

    case 10: $month = 'Oktober'; break;

    case 11: $month = 'November'; break;

    case 12: $month = 'Desember'; break;

    default: $month = ''; break;

  }

  

  return $month;

}



function get_mysql_long_date($mysql_timestamp)

{

  $new_date = (int)substr($mysql_timestamp, 8, 2).' ';

  $new_date .= get_textual_month(substr($mysql_timestamp, 5, 2)).' ';

  $new_date .= substr($mysql_timestamp, 0, 4);

  

  return $new_date;

}



function tanggal_surat($a='01/01/1090'){
		$t=$a;
		$d=substr($t,0,2);
		$m=substr($t,3,2);
		$y=substr($t,6,4);
		$php_timestamp=mktime(0, 0, 0, $m  , $d, $y);
		
		$array_bulan = array(1=>'Januari','Februari','Maret', 'April', 'Mei', 'Juni','Juli','Agustus','September','Oktober', 'November','Desember');
		$tanggal = date('j',$php_timestamp);
		$bulan = $array_bulan[date('n',$php_timestamp)];
		$tahun = date('Y',$php_timestamp);
		return $tanggal ." ". $bulan ." ".$tahun;
}

function baca_tanggal($a='01/01/1090'){
		$t=$a;
		$d=substr($t,0,2);
		$m=substr($t,3,2);
		$y=substr($t,6,4);
		$php_timestamp=mktime(0, 0, 0, $m  , $d, $y);
		
		$array_hari = array(1=>'Senin','Selasa','Rabu','Kamis','Jumat', 'Sabtu','Minggu');
		$hari = $array_hari[date('N',$php_timestamp)];
		return $hari." tanggal ". baca($d*1) ." bulan ". baca($m*1) ." tahun ".baca($y*1); 	
}


 function baca($n) {
    $dasar = array(1 => 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam','tujuh', 'delapan', 'sembilan');
    $angka = array(1000000000, 1000000, 1000, 100, 10, 1);
    $satuan = array('milyar', 'juta', 'ribu', 'ratus', 'puluh', '');
	 $str='';
    $i = 0;
    if($n==0){
       $str = "nol";
    }else{
       while ($n != 0) {
          $count = (int)($n/$angka[$i]);
      if ($count >= 10) {
          $str .= $this->baca($count). " ".$satuan[$i]." ";
      }else if($count > 0 && $count < 10){
          $str .= $dasar[$count] . " ".$satuan[$i]." ";
      }
      $n -= $angka[$i] * $count;
      $i++;
       }
       $str = preg_replace("/satu puluh (\w+)/i", "\\1 belas", $str);
       $str = preg_replace("/satu (ribu|ratus|puluh|belas)/i", "se\\1", $str);
    }
    return $str;
  }




function getDateIndonesiaPendek($date='dd/mm/yyyy'){
	$exp = explode("/",$date);
	$array_bulan = array(1=>'Jan','Feb','Mar', 'Apr', 'Mei', 'Jun','Jul','Agus','Sept','Okt', 'Nov','Des');
	if(count($exp)==3){
		$m = (int) $exp[1];
		return $exp[0].'-'.$array_bulan[$m].'-'.$exp[2];
	} else {
		return '';	
	}
}

function hari_tanggal_terbilang($time=0){
	$array_hari = array(1=>'senin','selasa','rabu','kamis','jumat', 'sabtu','minggu');
	$array_bulan = array(1=>'januari','februari','maret', 'spril', 'mei', 'juni','juli','agustus','september','oktober', 'november','desember');
	$hari = $array_hari[date('N',$time)];
	$tanggal = date('d',$time);
	$bulan = $array_bulan[date('n',$time)];
	$tahun = date('Y',$time);
	return array('HARI'=>ucfirst($hari),'TANGGAL'=>ucfirst(baca($tanggal*1)),'BULAN'=>ucfirst($bulan),'TAHUN'=>$tahun);
}


function get_mysql_short_date($mysql_timestamp)

{

  $new_date = substr($mysql_timestamp, 8, 2).'-';

  $new_date .= substr($mysql_timestamp, 5, 2).'-';

  $new_date .= substr($mysql_timestamp, 0, 4);

  

  return $new_date;

}



function get_php_long_date($php_timestamp)

{

  $new_date = date('d', $php_timestamp).' ';

  $new_date .= get_textual_month(date('m', $php_timestamp)).' ';

  $new_date .= date('Y', $php_timestamp);

  

  return $new_date;

}

function getIndonesia($php_timestamp){
	$array_hari = array(1=>'Senin','Selasa','Rabu','Kamis','Jumat', 'Sabtu','Minggu');
	$array_bulan = array(1=>'Januari','Februari','Maret', 'April', 'Mei', 'Juni','Juli','Agustus','September','Oktober', 'November','Desember');
	$hari = $array_hari[date('N',$php_timestamp)];
	$tanggal = date('j',$php_timestamp);
	$bulan = $array_bulan[date('n',$php_timestamp)];
	$tahun = date('Y',$php_timestamp);
    echo $hari . ", " . $tanggal ." ". $bulan ." ".$tahun; 
}
function getIndonesia2($php_timestamp){
	$array_hari = array(1=>'Senin','Selasa','Rabu','Kamis','Jumat', 'Sabtu','Minggu');
	$array_bulan = array(1=>'Januari','Februari','Maret', 'April', 'Mei', 'Juni','Juli','Agustus','September','Oktober', 'November','Desember');
	$hari = $array_hari[date('N',$php_timestamp)];
	$tanggal = date('j',$php_timestamp);
	$bulan = $array_bulan[date('n',$php_timestamp)];
	$tahun = date('Y',$php_timestamp);
    return $tanggal ." ". $bulan ." ".$tahun; 
}

function getIndonesia3($php_timestamp){
	$array_hari = array(1=>'Senin','Selasa','Rabu','Kamis','Jumat', 'Sabtu','Minggu');
	$array_bulan = array(1=>'Januari','Februari','Maret', 'April', 'Mei', 'Juni','Juli','Agustus','September','Oktober', 'November','Desember');
	$hari = $array_hari[date('N',$php_timestamp)];
	$tanggal = date('j',$php_timestamp);
	$bulan = $array_bulan[date('n',$php_timestamp)];
	$tahun = date('Y',$php_timestamp);
    return $hari.", ". $tanggal ." ". $bulan ." ".$tahun; 
}


function create_time_indonesia3($a='01/01/1090'){
		$t=$a;
		$d=substr($t,0,2);
		$m=substr($t,3,2);
		$y=substr($t,6,4);
		return getIndonesia3(mktime(0, 0, 0, $m  , $d, $y));
}

function date2unix($a='01/01/1970'){
		$t=$a;
		$d=substr($t,0,2);
		$m=substr($t,3,2);
		$y=substr($t,6,4);
		return mktime(0, 0, 0, $m  , $d, $y);
}

function create_time_indonesia2($a='01/01/1090'){
		$t=$a;
		$d=substr($t,0,2);
		$m=substr($t,3,2);
		$y=substr($t,6,4);
		return getIndonesia2(mktime(0, 0, 0, $m  , $d, $y));
}

function PhpTimeToOLEDateTime($timestamp)
{
	$a_date = getdate ($timestamp);
	$year= $a_date['year']; //this year
	$partial_days = ($year-1900)*365;//days elapsed since 1-1-1900
	//let's calculate how many 29 february from 1900 to first day on this year
	$partial_days +=(int)(($year-1) / 4); //each 4 years a leap year since year 0
	$partial_days -= (int)(($year-1) / 100); //each 100 years skip a leap
	$partial_days += (int)(($year-1) / 400); //each 400 years add a leap
	$partial_days -= 460; //459 leap years before 1900 + 1 for math (year 0 does not exist)
	$partial_days += $a_date['yday'];

	$seconds = $a_date['hours'] * 3600;
	$seconds += $a_date['minutes'] * 60;
	$seconds += $a_date['seconds'];

	$d = (double) $partial_days;
	$d +=  ((double)$seconds)/86400.0;

	return $d;
}

function tglAkhirBulan($thn,$bln){
	$bulan[1]='31';
	$bulan[2]='28';
	$bulan[3]='31';
	$bulan[4]='30';
	$bulan[5]='31';
	$bulan[6]='30';
	$bulan[7]='31';
	$bulan[8]='31';
	$bulan[9]='30';
	$bulan[10]='31';
	$bulan[11]='30';
	$bulan[12]='31';

	if ($thn%4==0){
		$bulan[2]=29;
	}
	return $bulan[$bln];
}

function convert_tgl($tgl,$format){
	if(strlen($tgl) > 1){
		if($format == 'd/m/y'){
			$tgl_exp = explode('/',$tgl);
			$tgl_result = $tgl_exp[0] . ' ' . get_textual_month($tgl_exp[1]) . ' ' . $tgl_exp[2];
		}
		else if($format == 'd-m-y'){
			$tgl_exp = explode('-',$tgl);
			$tgl_result = $tgl_exp[0] . ' ' . get_textual_month($tgl_exp[1]) . ' ' . $tgl_exp[2];				
		}
		else if($format == 'y-m-d'){
			$tgl_exp = explode('-',$tgl);
			$tgl_result = $tgl_exp[2] . ' ' . get_textual_month($tgl_exp[1]) . ' ' . $tgl_exp[0];
		}
		else if($format == 'y/m/d'){
			$tgl_exp = explode('/',$tgl);
			$tgl_result = $tgl_exp[2] . ' ' . get_textual_month($tgl_exp[1]) . ' ' . $tgl_exp[0];
		}
	}
	else{
		$tgl_result = '';
	}
	return $tgl_result;
}

// Helper untuk menghitung jumlah hari kerja tidak termasuk sabtu, minggu & hari libur yang diinsert dalam array
function getJumlahHariKerja($tglawal,$tglakhir,$holidays){
	$tglawal = strtotime($tglawal);
	$tglakhir = strtotime($tglakhir);
	
	//hitung jumlah hari dari tgl awal dan tgl akhir
	$days = ($tglakhir - $tglawal) / 86400 + 1;
	
	$no_full_weeks = floor($days / 7);
	$no_remaining_days = fmod($days, 7);
	
	//Hitung minggu awal dan minggu akhir...return Senin = 1, Selasa = 2,...
	$minggu_awal = date("N", $tglawal);
	$minggu_akhir = date("N", $tglakhir);
	
	//---->Untuk tahun kabisat ketika Februari memiliki 29 hari. Dapat ditambahkan disini
	if($minggu_awal <= $minggu_akhir){
		if ($minggu_awal <= 6 && 6 <= $minggu_akhir) 
			$no_remaining_days--;
		if ($minggu_awal <= 7 && 7 <= $minggu_akhir) 
			$no_remaining_days--;
	}
	else{
		if ($minggu_awal==7) {
			// Jika mulai Senin, kurangi 1 hari
			$no_remaining_days--;
			
			if ($minggu_akhir==6) {
				// jika tanggal akhir adalah hari Sabtu, maka kita kurangi hari lain
				$no_remaining_days--;
			}
		}
		else {
			$no_remaining_days -= 2;
		}	
	}	
	$hariKerja = $no_full_weeks * 5;	
	if($no_remaining_days > 0){
	  $hariKerja += $no_remaining_days;
	}
	foreach($holidays as $holiday){
		$time_stamp=strtotime($holiday);
		//echo $time_stamp.' ,';
		//Jika liburan tidak jatuh di akhir pekan
		if ($tglawal <= $time_stamp && $time_stamp <= $tglakhir && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
			$hariKerja--;
	}
	//return (int)abs($hariKerja);
	return (int)$hariKerja;
}

function getJumlahHariKerjaNoHoliday($from, $to)
{
	$workingDays = [1, 2, 3, 4, 5, 6, 7]; # date format = N (1 = Monday, ...)
	$holidayDays = [''];
	// $holidayDays = ['*-12-25', '*-01-01', '2013-12-23']; # variable and fixed holidays

	$from = new DateTime($from);
	$to = new DateTime($to);
	$to->modify('+1 day');
	$interval = new DateInterval('P1D');
	$periods = new DatePeriod($from, $interval, $to);

	$days = 0;
	foreach ($periods as $period) {
		if (!in_array($period->format('N'), $workingDays)) continue;
		if (in_array($period->format('Y-m-d'), $holidayDays)) continue;
		if (in_array($period->format('*-m-d'), $holidayDays)) continue;
		$days++;
	}
	return (int) $days;
}

function list_tanggal_kerja($start_date, $end_date, $holidays = array()) {
	$dates = array();
	$current_date = strtotime($start_date);
	$end_date = strtotime($end_date);
	while ($current_date <= $end_date) {
		if (date('N', $current_date) < 6 && !in_array(date('Y-m-d', $current_date), $holidays)) {
			$dates[] = date('Y-m-d', $current_date);
		}
		if ($current_date <= $end_date) {
			$current_date = strtotime('+1 day', $current_date);
		}
	}
	return $dates;
}	

function add_business_days($start_date,$businessdays,$holidays=array()){
	$dates = array();
	$current_date = strtotime($start_date);
	$end_date = strtotime('+'.$businessdays.' day',$current_date);
	while ($current_date < $end_date) {
		if (date('N', $current_date) < 6 && !in_array(date('Y-m-d', $current_date), $holidays)) {
			$dates[] = date('Y-m-d', $current_date);
		}
		if ($current_date <= $end_date) {
			$current_date = strtotime('+1 day', $current_date);
		}
	}
	return $dates;
}

function addWorkDays($start_date,$length,$holidays=array()) {
	$dates = array();
	$current_date = strtotime($start_date);
	$end_date = strtotime('+365 day',$current_date);
	while ($current_date < $end_date) {
		if (date('N', $current_date) < 6 && !in_array(date('Y-m-d', $current_date), $holidays)) {
			$dates[] = date('Y-m-d', $current_date);			
		}
		
		if ($current_date <= $end_date) {
			$current_date = strtotime('+1 day', $current_date);
		}			
	}	
	return ($dates[$length-1]);	
}

function isWeekend($date) {
	$weekDay = date('w', strtotime($date));
	return ($weekDay == 0 || $weekDay == 6);
}		

function hariind($hariid){
	$hari = '';
	switch($hariid){
		case 1:
			$hari = 'Senin';
			break;
		case 2:
			$hari = 'Selasa';
			break;
		case 3:
			$hari = 'Rabu';
			break;
		case 4:
			$hari = 'Kamis';
			break;
		case 5:
			$hari = 'Jumat';
			break;
		case 6:
			$hari = 'Sabtu';
			break;
		default:
			$hari = 'Minggu';
			break;
	}
	return $hari;
}



/* End of file tanggal_helper.php */

/* Location: ./application/helpers/tanggal_helper.php */