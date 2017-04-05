<?php

session_start();

$user = 'nataliaa';
$pass = '1234';
$host = 'mysql.metropolia.fi';
$dbname = 'nataliaa';

//Tietokantayhteys sulkeutuu automaattisesti kun </htm> eli sivun scripti loppuu
//Normaali olion elinkaari
try { //Avataan kahva tietokantaan
    $DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    // virheenkäsittely: virheet aiheuttavat poikkeuksen
    $DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    // käytetään  yleistä nerkistöä utf8
    $DBH->exec("SET NAMES utf8;");
} catch(PDOException $e) {
    echo "Yhteysvirhe.";
    file_put_contents('log/DBErrors.txt', 'Connection: '.$e->getMessage()."\n", FILE_APPEND);
}//HUOM hakemistopolku!

//==== Redirect... Try PHP header redirect, then Java redirect, then try http redirect.:
function redirect($url){
	if (!headers_sent()){	//If headers not sent yet... then do php redirect
    	header('Location: '.$url); exit;
	}else{                	//If headers are sent... do java redirect... if java disabled, do html redirect.
    	echo '<script type="text/javascript">';
    	echo 'window.location.href="'.$url.'";';
    	echo '</script>';
    	echo '<noscript>';
    	echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
    	echo '</noscript>'; exit;
	}
}//==== End -- Redirect

// get ticket number
function getTicketnum($fname, $lname) {
    try {
        $_SESSION['message'] = "SELECT ticketnum from Ticket_Info WHERE firstname = '" . $fname . "' AND  lastname = '" . $lname . "';";
            $stm = $DBH->prepare("SELECT ticketnum from Ticket_Info WHERE firstname = '" . $fname . "' AND  lastname = '" . $lname . "';");
             if ($stm->execute()) {
                 
                return $stm->fetch();
            }
        } catch(PDOException $e) {
            $_SESSION['message'] = "error"; //$e.getMessage()
        }
}

function viitenumero($laskunro){
    if(strlen($laskunro) > 19) return 0;
    $kertoimet = array('7','3','1','7', '3','1','7','3','1','7','3','1','7','3','1','7','3','1','7');
    $tarkiste = 0;
    $j = 0;
    $tmp = $laskunro;
    settype($tmp, "string");
    for($i=strlen($tmp)-1; $i>-1; $i--){
        $tarkiste = $tarkiste + $kertoimet[$j++] * intval(substr($tmp, $i, 1));
     }
     $tarkiste = ceil($tarkiste / 10) * 10 - $tarkiste;
     $viite = "$laskunro$tarkiste";
    return $viite;

}

function random() {
    return random_int(1000, 100000000);
}


//==== Turn on HTTPS - Detect if HTTPS, if not on, then turn on HTTPS:

function SSLon(){
	if($_SERVER['HTTPS'] != 'on'){
    	$url = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    	redirect($url);
	}
}

//==== End -- Turn On HTTPS




//==== Turn Off HTTPS -- Detect if HTTPS, if so, then turn off HTTPS:
function SSLoff(){
	if($_SERVER['HTTPS'] == 'on'){
    	$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    	redirect($url);
	}
}//==== End -- Turn Off HTTPS






?>