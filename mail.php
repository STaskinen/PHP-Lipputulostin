<?php
include('includes/iheader.php');

$email = new PHPMailer();

$email->From = 'esimerkki@esimerkki.com';
$email->FromName = 'Erkko Esimerkki @ Lippuja Kaikille';
$email->Subject = "Kopio lipusta";
$email->Body = "Lippunne: ";
$file_to_attach = 'attach/sup.pdf';
$email->AddAddress('natalia.alam@metropolia.fi');

$email->AddAttachment($file_to_attach, 'sup.pdf');

$email->Send();


?>