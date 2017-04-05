<?php
include_once("includes/iheader.php");
if(isset($_POST["save"])) {
    $data['firstname'] = $_POST['givenFirstname'];
    $data['lastname'] = $_POST['givenLastname'];
    $data['email'] = $_POST['givenMail'];
    $data['referencenum'] = random();
    $_SESSION['fName'] = $_POST['givenFirstname'];
    $_SESSION['lName'] = $_POST['givenLastname'];
    $_SESSION['Mail'] = $_POST['givenMail'];
    
    
    try {
            $stm = $DBH->prepare("INSERT into Ticket_Info (firstname, lastname, email, referencenum) VALUES(:firstname, :lastname, :email, :referencenum);");
             if ($stm->execute($data)) {
                //$_SESSION['name'] = $data['name'];
                //$_SESSIN['email'] = $data['email'];
                $_SESSION['message'] = "Nyt voit ostaa lipun!";
                $_SESSION['registered'] = 'yes';
                // $_SESSION['loggedIn] = 'yes'; // jos rekisteröinti onnistunut -> kirjautunut suoraan sisään
                redirect("print.php");
            } else {
                $_SESSION['message'] = "Lipun ostaminen ei onnistu.";
                redirect("index.php");
            }
        } catch(PDOException $e) {
            $_SESSION['message'] = "error"; //$e.getMessage()
            redirect("index.php");
        }
    }
?>
