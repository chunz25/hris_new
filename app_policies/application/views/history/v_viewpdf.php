<?php 
// The location of the PDF file 
// on the server 
$path = config_item('eservices_upload_dok_path');
$filename = $path . $no;
// Header content type 
header("Content-type: application/pdf"); 
header("Content-Length: " . filesize($filename)); 
// Send the file to the browser. 
readfile($filename); 
?> 