<!DOCTYPE html>
<html lang="en">
  <head>
  <link href="style.css" rel="stylesheet" media="all" type="text/css">
  <script type="text/javascript" src="monhoraireSNCF.scripts.js"></script> 
    <meta charset="utf-8">
    <meta name="generator" content="CoffeeCup HTML Editor (www.coffeecup.com)">
    <meta name="dcterms.created" content="mer., 28 juin 2017 19:12:29 GMT">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Signaler un retard</title>
    
    <!--[if IE]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>
  <body>
  <form name="form" method="post" action="http://127.0.0.1/pigeons-du-rail/userEvent.php">
	<datalist id="stations">
	<?php include 'data/StationList.html' ?>
	</datalist>
	<input type="text" name="trainStop" list="stations" required>
  </form>
  </body>