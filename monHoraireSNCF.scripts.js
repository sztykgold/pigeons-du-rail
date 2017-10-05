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
	    displayMessage("Nom de gare d'arrivée invalide", true);
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
		    var initialTime = document.form.initialTime.value;

		    // regular expression to match required time format
            re = /^\d{1,2}:\d{2}([ap]m)?$/;

            if(initialTime == '' || !initialTime.match(re)) 
			{
                displayMessage(
				     "vous devez préciser l'heure d'arrivée prévue pour pouvoir identifier le " 
					 + trainType, true);
                return false;
            }
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
	    //train stop validation
		if( !validateTrainStop() )
			return;	
	
		//nb minutes validation
		if( !validateLateDuration() )
	    	 return;
	}
	if( type == 'Deleted' )
	{
	    if( !validateTrainStartStop() )
		{
		    return;
		}
	}
	send(form);
}

document.getElementById("submitButton").addEventListener("onClick", validateForm);

function send(form)
{		 
		 var trainType = document.form.trainType.value
		 var trainNumber = document.form.trainNumber.value;
		 var userEmail = document.form.userEmail.value;
		 var lateDuration = document.form.lateDuration.value;
		 var trainStop = document.form.trainStop.value;
		 var initialTime = document.form.initialTime.value;

		 var msg = "Vous (" + userEmail + ") etes arrivé avec : " + lateDuration 
		 + " minutes de retard en gare de " + trainStop + " sur le " + trainType + " " + trainNumber; 
	
		 displayMessage(msg, false);
		 
		 var hiddenField = document.createElement("input");
         hiddenField.setAttribute("type", "hidden");
         hiddenField.setAttribute("name", "eventType");
         hiddenField.setAttribute("value", "Late");
  
  		 document.form.appendChild(hiddenField);
		 document.form.submit();
		 alert("Merci pour votre contribution");
}

function TrainTypeChanged()
{
 	if(document.getElementById('trainType').value == 'TER')
	{
	 	document.getElementById('idTrainLabel').innerHTML = 'Numéro ';
		document.getElementById('initialTimeLabel').style.visibility = 'hidden';
		document.getElementById('initialTime').style.visibility = 'hidden';
	}
    if(document.getElementById('trainType').value == 'RER')
	{
	 	document.getElementById('idTrainLabel').innerHTML = 'Code 4 lettres ';
		document.getElementById('initialTimeLabel').style.visibility = 'visible';
		document.getElementById('initialTime').style.visibility = 'visible';
	}
	if(document.getElementById('trainType').value == 'TRANSILIEN')
	{
	 	document.getElementById('idTrainLabel').innerHTML = 'Code 4 lettres ';
		document.getElementById('initialTimeLabel').style.visibility = 'visible';
		document.getElementById('initialTime').style.visibility = 'visible';
	}
}

function LoadLateForm()
{
	window.location.replace('Late.html'); 
}
 
function LoadFullForm()
{
    window.location.replace('Full.html'); 
} 

function LoadDeletedForm()
{
    window.location.replace('Deleted.html'); 
} 
