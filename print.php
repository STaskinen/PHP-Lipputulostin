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
$pdf->SetFontSize(20);
$pdf->SetTextColor(179,179,52);
$pdf->Cell(40,10,"Pääsylippu");
$pdf->Ln();
$pdf->Ln();
$pdf->SetTextColor(0,0,0);
$pdf->SetFontSize(14);
$pdf->Cell(40,10,"Nimi: " . $fname . " " . $lname);
$pdf->Ln();
$pdf->Cell(40,10,"Lipun numero:" . ($sup->ticketnum));
$pdf->Image('img/logo.png', 172, 1, 40);
$pdf->Image('qrcode.png', 170, 50, 40);

$pdf->Output('attachments/lippu.pdf', 'F');

// luodaan lasku: ensimmäinen maksuerä
$pdf = new TFPDF();
$pdf->AddPage();
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$pdf->SetFont('DejaVu','',14);
$pdf->SetFontSize(20);
$pdf->SetTextColor(179,179,52);
$pdf->Cell(40,10,"Lasku");
$pdf->Ln();
$pdf->Ln();
$pdf->SetTextColor(0,0,0);
$pdf->SetFontSize(14);
$pdf->Cell(40,10,"Nimi: " . $fname . " " . $lname);
$pdf->Ln();
$pdf->Cell(40,10,"Lipun numero: " . ($sup->ticketnum));
$pdf->Ln(145);
$pdf->SetFontSize(16);
$pdf->Cell(40,10,"Ensimmäinen maksuerä");
$pdf->Ln();
$pdf->Ln();
$pdf->SetFontSize(14);
$pdf->Cell(40,10,"Summa: 37,80 €");
$pdf->Ln();
$pdf->Cell(40,10,"Viitenumero: " . ($sup->referencenum));
$pdf->Ln();
$pdf->Cell(40,10,"Eräpäivä: 26.04.2017");
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(40,10,"Saaja: Lippuja Kaikille");
$pdf->Ln();
$pdf->Cell(40,10,"Saajan tilinumero: FI 09 0909 0909090909");
$pdf->Image('img/logo.png', 172, 1, 40);

// toinen maksuerä
$pdf->AddPage();
$pdf->SetFontSize(20);
$pdf->SetTextColor(179,179,52);
$pdf->Cell(40,10,"Lasku");
$pdf->Ln();
$pdf->Ln();
$pdf->SetTextColor(0,0,0);
$pdf->SetFontSize(14);
$pdf->Cell(40,10,"Nimi: " . $fname . " " . $lname);
$pdf->Ln();
$pdf->Cell(40,10,"Lipun numero: " . ($sup->ticketnum));
$pdf->Ln(145);
$pdf->SetFontSize(16);
$pdf->Cell(40,10,"Toinen maksuerä");
$pdf->Ln();
$pdf->Ln();
$pdf->SetFontSize(14);
$pdf->Cell(40,10,"Summa: 37,80 €");
$pdf->Ln();
$pdf->Cell(40,10,"Viitenumero: " . ($sup->referencenum));
$pdf->Ln();
$pdf->Cell(40,10,"Eräpäivä: 10.05.2017");
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(40,10,"Saaja: Lippuja Kaikille");
$pdf->Ln();
$pdf->Cell(40,10,"Saajan tilinumero: FI 09 0909 0909090909");
$pdf->Image('img/logo.png', 172, 1, 40);
$pdf->Output('attachments/lasku.pdf', 'F');

// luodaan sähköposti
$email = new PHPMailer();
$email->From = 'esimerkki@lippujakaikille.com';
$email->FromName = 'Erkko Esimerkki @ Lippuja Kaikille';
$email->Subject = "Kopio lipusta";
$email->Body = "Hei! Olette tilanneet lipun Lippuja Kaikille -lippupalvelusta. Lippu ja lasku on liitetty viestiin.";
$attachment1 = 'attachments/lippu.pdf';
$attachment2 = 'attachments/lasku.pdf';
$email->AddAddress($sup->email);

$email->AddAttachment($attachment1, 'lippu.pdf');
$email->AddAttachment($attachment2, 'lasku.pdf');

$email->Send();

$_SESSION['message'] = "Lippu ja lasku lähetetty sähköpostiin";
redirect("index.php");

?>
