<?php include("includes/iheader.php");
echo $_SESSION['message'];
//echo viitenumero(45354543);
//echo laskunumero();
?>
<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Lipun tilaus</title>
  <meta name="description" content="The HTML5 Herald">
  <meta name="author" content="SitePoint">

  <link rel="stylesheet" href="css/styles.css?v=1.0">
</head>
<body>
<form method="POST" action="setUserinfo.php">
    <br/>
    <label>Etunimi:</label><br/>
    <input type="text" name="givenFirstname" required><br/>
    <label>Sukunimi:</label><br/>
    <input type="text" name="givenLastname" required><br/>
    <label>Email:</label><br/>
    <input type="text" name="givenMail" required><br/><br/>
    <input id=btn type="submit" value="Tilaa lippu" name="save">
</form><br/>

<script src="js/scripts.js"></script>

</body>
</html>