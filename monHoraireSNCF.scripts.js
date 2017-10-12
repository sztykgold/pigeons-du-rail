function isEmail(email)
{      	 
    var emailReg = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    return emailReg.test(email); 
}

function displayMessage(msg, isError)
{ 		
    document.getElementById("err").innerHTML = msg;
    if(isError)
	{
        document.getElementById("err").style.color="red";
    }
	else
	{
	    document.getElementById("err").style.color="green";
	}
}

function validateEmail()
{
    var userEmail = document.getElementById("userEmail").value;

	//reset error message
	displayMessage("", false);
	
	//email validation
	if(!isEmail(userEmail))
	{
	    displayMessage("Entrez un email valide s'il vous plait.", true);
		return false;
	}
	return true;
}

function validateTrainStop()
{
    var trainStop = document.form.trainStop.value;
	var trainStopOk = (/^([a-zA-Z \-']+)$/).test(trainStop);
	
	if(! trainStopOk )
	{
	    displayMessage("Nom de gare  " + trainStop + " d'arrivée invalide", true);
		return false;
	}
	return true;
}

function validateTrainStartStop()
{
    var startStop = document.form.startStop.value;
	var startStopOk = (/^([a-zA-Z \-']+)$/).test(startStop);
	
	if(! startStopOk )
	{
	    displayMessage("Nom de gare de départ invalide", true);
		return false;
	}
	return true;
}

function validateTrainNumber()
{
    //   if TER => number must be int
	//   if RER/TRANSILIEN => number must be 4 letters and initial daparture time valid
	var trainType = document.form.trainType.value
	var trainNumber = document.form.trainNumber.value;
		 
	if(trainType == 'TER')
	{
	  	if( trainNumber.length == 0 || isNaN(trainNumber) )
		{
		    displayMessage("Le numéro de train doit être un nombre enfier !", true);
			return false;
		}
	}
    else
    {
        if(/^([a-zA-Z]{4})$/.test(trainNumber))
        {
			document.form.trainNumber.value = trainNumber.toUpperCase();			
        }
        else
        {
            displayMessage('Le code du train doit etre composé de 4 lettres !', true);
			return false;
		}
	}
	return true;
}

function validateLateDuration()
{
    var lateDuration = document.form.lateDuration.value;

	if( lateDuration.length == 0 || isNaN(lateDuration) )
	{
	    displayMessage("Merci d'indiquer le nombre de minutes de retard", true);
		return false;
	}
	var late = parseInt(lateDuration);

	if(late <= 0)
	{
		displayMessage("Un retard inférieur à 0 minutes ???", true);
		return false;
	}
	return true;
}

function validateForm(form)
{
 	//email is always required
	if(! validateEmail() )
		 return;
	
	var type = document.getElementById("eventType").value;

	if( type == '' )
	 	return;

	//train number validation :
	if( !validateTrainNumber() )
	    return;
				
	if( type == 'Late' )
	{
		//nb minutes validation
		if( !validateLateDuration() )
	    	 return;
	}
	if( type == 'Deleted' )
	{
	}
	if( type == 'Full' )
	{
	}
	send(form, type);
}

document.getElementById("submitButton").addEventListener("onClick", validateForm);

function send(form, type)
{		
		if(type == "Late")
		{
			 var trainType = document.form.trainType.value
			 var trainNumber = document.form.trainNumber.value;
			 var userEmail = document.form.userEmail.value;
			 var lateDuration = document.form.lateDuration.value;
			 var trainStop = document.form.trainStop.value;

			 var msg = "Vous (" + userEmail + ") etes arrivé avec : " + lateDuration 
			 + " minutes de retard en gare de " + trainStop + " sur le " + trainType + " " + trainNumber; 
		
			 displayMessage(msg, false);
		}
		if(type == "Full")
		{
			 var trainType = document.form.trainType.value
			 var trainNumber = document.form.trainNumber.value;
			 var userEmail = document.form.userEmail.value;

			 var msg = "Vous (" + userEmail + ") déclarez que le " + trainType + " " + trainNumber + " a été bondé."; 
		
			 displayMessage(msg, false);
		}
		if(type == "Deleted")
		{
			 var trainType = document.form.trainType.value
			 var trainNumber = document.form.trainNumber.value;
			 var userEmail = document.form.userEmail.value;

			 var msg = "Vous (" + userEmail + ") déclarez que le " + trainType + " " + trainNumber + " a été supprimé."; 
		
			 displayMessage(msg, false);
		}
		 var hiddenField = document.createElement("input");
         hiddenField.setAttribute("type", "hidden");
         hiddenField.setAttribute("name", "eventType");
         hiddenField.setAttribute("value", type);
  
  		 document.form.appendChild(hiddenField);
		 document.form.submit();
		 alert("Merci pour votre contribution");
}

function TrainTypeChanged()
{
 	if(document.getElementById('trainType').value == 'TER')
	{
	 	document.getElementById('idTrainLabel').innerHTML = 'Numéro ';
	}
    if(document.getElementById('trainType').value == 'RER')
	{
	 	document.getElementById('idTrainLabel').innerHTML = 'Code 4 lettres ';
	}
	if(document.getElementById('trainType').value == 'TRANSILIEN')
	{
	 	document.getElementById('idTrainLabel').innerHTML = 'Code 4 lettres ';
	}
}

function LoadLateForm()
{
	window.location.replace('Late.php'); 
}
 
function LoadFullForm()
{
    window.location.replace('Full.php'); 
} 

function LoadDeletedForm()
{
    window.location.replace('Deleted.php'); 
} 
