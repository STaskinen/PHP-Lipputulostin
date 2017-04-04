<?php
include('includes/iheader.php');
require('fpdf/tfpdf.php');
$sup = "Testing";
$arnold = "Arnodl ";
$message = $_SESSION['message'];
$fname = $_SESSION['fName'];
$lname = $_SESSION['lName'];
$sup;
//$tNum = getTicketnum("q","a");
try {
            $stm = $DBH->prepare("SELECT ticketnum, email from Ticket_Info WHERE firstname = '" . $fname . "' AND  lastname = '" . $lname . "';");
             if ($stm->execute()) {
                 $stm->setFetchMode(PDO::FETCH_OBJ);
                $sup = $stm->fetch();
            }
        } catch(PDOException $e) {
            $_SESSION['message'] = "error"; //$e.getMessage()
        }

$pdf = new TFPDF();
$pdf->AddPage();
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$pdf->SetFont('DejaVu','',14);
$pdf->Cell(40,10,"Pääsylippu ");
$pdf->Ln();
$pdf->Cell(40,10,"Nimi: " . $fname . " " . $lname);
$pdf->Ln();
$pdf->Cell(40,10,"Lipun numero: " . ($sup->ticketnum));
$pdf->Image('img/logo.png', 180, 1, 30);
$pdf->Output('attach/sup.pdf', 'F');
$email = new PHPMailer();

$email->From = 'esimerkki@esimerkki.com';
$email->FromName = 'Erkko Esimerkki @ Lippuja Kaikille';
$email->Subject = "Kopio lipusta";
$email->Body = "Lippunne: ";
$file_to_attach = 'attach/sup.pdf';
$email->AddAddress($sup->email);

$email->AddAttachment($file_to_attach, 'sup.pdf');

$email->Send();

$_SESSION['message'] = "Lippu lähetetty sähköpostiin";
redirect("index.php");

?>
