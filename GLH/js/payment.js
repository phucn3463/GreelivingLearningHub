/* Validate function on submit function */
function validate() {
    var name = $("#name").val();
    var visa = $("#visa").prop("checked");
    var masterCard = $("#masterCard").prop("checked");
    var americanExpress = $("#americanExpress").prop("checked");
    var cardNumber = $("#cardNumber").val();
    var cvvNumber = $("#cvvNumber").val();

    var errMsg = "";
    var result = true;

    /* Check if all needed inputs are filled and input is in correct form */

    /* Payment information Section validate */
    if (name == "") {
        errMsg += "Name cannot be empty.\n";
    }
    
    if ((visa == "")&&(masterCard == "")&&(americanExpress == "")) {
        errMsg += "Payment option cannot be empty.\n";
    }
    else {
        if ((visa != "")&&(masterCard == "")&&(americanExpress == "")&&(cardNumber != "")) {
            if (cardNumber.length != 16) {
                errMsg += "Visa card requires 16 digits.\n"
            }
        }
        if ((visa == "")&&(masterCard != "")&&(americanExpress == "")&&(cardNumber != "")) {
            if (cardNumber.length != 16) {
                errMsg += "Mastercard card requires 16 digits.\n"
            }
        }
        if ((visa == "")&&(masterCard == "")&&(americanExpress != "")&&(cardNumber != "")) {
            if (cardNumber.length != 15) {
                errMsg += "American Express card requires 15 digits.\n"
            }
        }
    }
    if (cardNumber == "") {
        errMsg += "Card Number cannot be empty.\n";
    }
    if (cvvNumber == "") {
        errMsg += "CVV Number cannot be empty.\n";
    } else if (cvvNumber.length != 3) {
        errMsg += "CVV number requires 3 digits.\n"
    }

    /*If all requires are completed*/
    if(errMsg != "") {		    //if there is an error, display the error message
		alert(errMsg);
		result = false;
	} 
	return result;	
}


/*Allow the form to be submitted only if all the input data are valid*/
function init () {
    /* Validate function on submit */
	$("#paymentform").submit(validate);
}


$(document).ready(init);