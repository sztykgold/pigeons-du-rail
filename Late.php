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
  <!--<form name="form" method="post" action="http://www.pigeons-du-rail.fr/userEvent.php">-->
	<input type="hidden" name="eventType" id="eventType" value="Late" />
	
	<datalist id="station-list">
	<?php include 'data/StationList.html' ?>
	</datalist>
	
	<table>
  <tr>
    <td><label id="labelTrain">Train</label></td>
	<td><select name="trainType" id="trainType" onChange="TrainTypeChanged();">
				<option value="TER">TER</option>
				<option value="RER">RER</option>
				<option value="TRANSILIEN">Transilien</option>
		</select>
	</td>
  	<td><label id="idTrainLabel">Numéro </label><input type="text" name="trainNumber" id="trainNumber" required/></td>
	<td class="tooltip" data-tooltip="Heure initialiement prévue à votre gare de d'arrivée (ce champ sert à identifier le train)">
	    <label id="initialTimeLabel" style="visibility:hidden;">Heure d'arrivée prévue </label>
		<input type="time" id="initialTime" name="initialTime" style="visibility:hidden;" onLoad="onIntialTimeLoad();"/>
	</td>
  </tr>		   
  
  <tr>
    <td>Votre email</td>
  	<td class="tooltip" data-tooltip="Votre email ne sera jamais utilisé pour autre chose que vous informer mensuellement de votre préjudice."><div ><input type="email" id="userEmail" name="userEmail" required/></div></td>
  </tr>
  
  <tr>
    <td>Gare d'arrivée </td>
  	<td class="tooltip" data-tooltip="Gare où vous avez subi le retard"><input type="text" name="trainStop" list="station-list" required></td>
  </tr>

  <tr>
    <td>Retard (min) </td>
  	<td class="tooltip" data-tooltip="Nombre total de minutes de retard à votre gare d'arrivée"><input type="number" id="lateDuration" name="lateDuration" required/></td>
  </tr>
  
  <tr>
    <td></td>
  	<td align="center"><input type="button" id="submitButton" name="submitButton" value="Envoyer" onClick="validateForm(form)"/></td>
  </tr>

  </table>
  </form>
  
  <h2 id="err"/>
  
  </body>
</html>