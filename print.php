<?php
include('includes/iheader.php');
include('includes/phpqrcode/qrlib.php');
require('fpdf/tfpdf.php');

$message = $_SESSION['message'];
$fname = $_SESSION['fName'];
$lname = $_SESSION['lName'];
$sup;
try {
            $stm = $DBH->prepare("SELECT ticketnum, email, referencenum from Ticket_Info WHERE firstname = '" . $fname . "' AND  lastname = '" . $lname . "';");
             if ($stm->execute()) {
                 $stm->setFetchMode(PDO::FETCH_OBJ);
                $sup = $stm->fetch();
            }
        } catch(PDOException $e) {
            $_SESSION['message'] = "error"; //$e.getMessage()
        }

$referencenum = viitenumero($sup->referencenum);
QRcode::png($referencenum, 'qrcode.png');


// luodaan lippu
$pdf = new TFPDF();
$pdf->AddPage();
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$pdf->SetFont('DejaVu','',14);
$pdf->Cell(40,10,"Pääsylippu ");
$pdf->Ln();
$pdf->Cell(40,10,"Nimi: " . $fname . " " . $lname);
$pdf->Ln();
$pdf->Cell(40,10,"Lipun numero: " . ($sup->ticketnum));
$pdf->Image('img/logo.png', 172, 1, 40);
$pdf->Image('qrcode.png', 170, 50, 40);
$pdf->Output('sup.pdf', 'F');

// luodaan lasku: ensimmäinen maksuerä
$pdf2 = new TFPDF();
$pdf2->AddPage();
$pdf2->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$pdf2->SetFont('DejaVu','',14);
$pdf2->Cell(40,10,"Lasku");
$pdf2->Ln();
$pdf2->Cell(40,10,"Nimi: " . $fname . " " . $lname);
$pdf2->Ln();
$pdf2->Cell(40,10,"Lipun numero: " . ($sup->ticketnum));
$pdf2->Ln(205);
$pdf2->Cell(40,10,"Ensimmäinen maksuerä");
$pdf2->Ln();
$pdf2->Cell(40,10,"Summa: 37,80 €");
$pdf2->Ln();
$pdf2->Cell(40,10,"Viitenumero: " . ($sup->referencenum));
$pdf2->Ln();
$pdf2->Cell(40,10,"Eräpäivä: 18.05.2017");
$pdf2->Image('img/logo.png', 172, 1, 40);

// toinen maksuerä
$pdf2->AddPage();
$pdf2->Cell(40,10,"Lasku");
$pdf2->Ln();
$pdf2->Cell(40,10,"Nimi: " . $fname . " " . $lname);
$pdf2->Ln();
$pdf2->Cell(40,10,"Lipun numero: " . ($sup->ticketnum));
$pdf2->Ln(205);
$pdf2->Cell(40,10,"Toinen maksuerä");
$pdf2->Ln();
$pdf2->Cell(40,10,"Summa: 37,80 €");
$pdf2->Ln();
$pdf2->Cell(40,10,"Eräpäivä: 18.07.2017");
$pdf2->Image('img/logo.png', 172, 1, 40);
$pdf2->Output('lasku.pdf', 'F');

// luodaan sähköposti
$email = new PHPMailer();
$email->From = 'esimerkki@esimerkki.com';
$email->FromName = 'Erkko Esimerkki @ Lippuja Kaikille';
$email->Subject = "Kopio lipusta";
$email->Body = "Hei! Olette tilanneet lipun Lippuja Kaikille -lippupalvelusta. Lippu ja lasku liitetty viestiin.";
$attachment1 = 'sup.pdf';
$attachment2 = 'lasku.pdf';
$email->AddAddress($sup->email);

$email->AddAttachment($attachment1, 'sup.pdf');
$email->AddAttachment($attachment2, 'lasku.pdf');

$email->Send();

$_SESSION['message'] = "Lippu ja lasku lähetetty sähköpostiin";
redirect("index.php");

?>
