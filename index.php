<?php include("includes/iheader.php");
echo $_SESSION['message']
?>
<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Osta lippu</title>
  <meta name="description" content="The HTML5 Herald">
  <meta name="author" content="SitePoint">

  <link rel="stylesheet" href="css/styles.css?v=1.0">
</head>
<body>
<form method="POST" action="setUserinfo.php">
    <label>First name:</label><br/>
    <input type="text" name="givenFirstname"><br/>
    <label>Last name:</label><br/>
    <input type="text" name="givenLastname"><br/>
    <label>Email:</label><br/>
    <input type="text" name="givenMail"><br/>
    <input type="submit" value="Get ticket" name="save">
</form><br/>

<a href="print.php"><button type="button">Click me!</button></a> 
<script src="js/scripts.js"></script>

</body>
</html>