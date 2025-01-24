<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function format_currency($currency)
{
  $result = number_format($currency, 0, ',', '.');
  
  return ($result != 0 ? $result : '-');
}

function Terbilang($satuan){
    $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    if($satuan < 12)
        return " " . $huruf[$satuan];
    elseif($satuan < 20)
        return Terbilang($satuan - 10) . "belas";
    elseif($satuan < 100)
        return Terbilang($satuan / 10) . " puluh" . Terbilang($satuan % 10);
    elseif($satuan < 200)
        return " seratus" . Terbilang($satuan - 100);
    elseif($satuan < 1000)
        return Terbilang($satuan / 100) . " ratus" . Terbilang($satuan % 100);
    elseif($satuan < 2000)
        return " seribu" . Terbilang($satuan - 1000);
    elseif($satuan < 1000000)
        return Terbilang($satuan / 1000) . " ribu" . Terbilang($satuan % 1000);
    elseif($satuan < 1000000000)
        return Terbilang($satuan / 1000000) . " juta" . Terbilang($satuan % 1000000);
    elseif($satuan >= 1000000000)
        echo "Nilai uang terlalu besar";
}



/* End of file currency_helper.php */
/* Location: ./application/helpers/currency_helper.php */