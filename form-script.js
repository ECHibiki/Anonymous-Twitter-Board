//Files and character counter
document.addEventListener("FileUploaded", function(e){
			console.log("end-of-end");
			console.log(image_load_arr)
			var all_checked = !image_load_arr.some(function(image_load_bool){
				return (!image_load_bool)
			});
			if(all_checked){
				console.log("sent");
				var form_submision = new XMLHttpRequest();
				form_submision.open("POST", "/add-to-queue");
				form_submision.onload = function(){
					var code = form_submision.response.split(" ")[0];
					//if( code == "-1") alert("Multiple submissions detected");
					//else if(code == "-2")alert("Error");
					//window.location.href= "/confirmation";
				};
				form_submision.send(form_submision_values);
			}
	});
var form_submision_values; 

var character_counter = document.getElementById('CharacterCount');
var error_msg = document.getElementById('errorMsg');
var error_msg_text = document.createTextNode("");
error_msg.appendChild(error_msg_text);
var textarea = document.getElementById('Comment');
var submit = document.getElementById('submit-button');
submit.removeAttribute('disabled');

var image_load_arr = [false,false,false,false];

var CHARACTER_LIMIT =  280;

function checkIfSubmitToBeDisabled(){
	characterCountColoring();
	//Check comment
	var length = textarea.value.length;
	if(length == 0) {
		submit.setAttribute('disabled',1);
		error_msg.textContent = "Input a comment and/or file";
	}
	else if(length > CHARACTER_LIMIT){
		submit.setAttribute('disabled',1);
		error_msg.textContent = "Character count exceeded(>500"+ CHARACTER_LIMIT + ")";
	}
	else{
		submit.removeAttribute('disabled');
		error_msg.innerHTML = "Click to submit";
	}
	//check all file fontainers
	for(var i = 1 ; i <= 4; i++){
		if(document.getElementById("f" + i).files[0] != undefined){ // check if exists
			console.log(document.getElementById("f" + i).files[0].name);
			if(document.getElementById("f" + i).files[0].name.indexOf(".webm") >-1){ //
				submit.setAttribute('disabled',1);
				console.log(error_msg_text);
				error_msg.textContent = "Twitter does not support .webm. Use .mp4 instead.";
				break;
			}
			if(length == 0) {
				console.log(document.getElementById("f" + i).files[0] )
				submit.removeAttribute('disabled');
				error_msg.nodeValue = "Click to submit";
			}
		}
	}
}

function characterCountColoring(){
	var length = textarea.value.trim().length;
	var red = 0; var blue = 100; var green = 100;
	if(length == 0){
		red = 0; blue = 0; green = 0;
	}
	else if(length > CHARACTER_LIMIT){
		red = 255; blue = 0; green = 0;
	}
	else{
		red = Math.ceil(length/CHARACTER_LIMIT * 180);
	}
	character_counter.innerHTML = '<span style=\'color:rgb(' + red + ',' + green + ',' + blue + ')\'>' + length + '</span>'
}

function setFileListener(file){
	file_node = document.getElementById(file);
	(function(_file_node){
		_file_node.addEventListener("change", checkIfSubmitToBeDisabled);
	})(file_node);
}

//use an RSA token to prevent multiple form submissions
//Ticket gen recieves the comment, the IP of the sender and creates a random value identifying the token.
//This token primarily uses the IP and random value to decide if a form has been submitted multiple times.
//Comments are checked against the server before file proccessing for speed and hidden in the token. comment is not sent in the files POST request. 
function submitProcess(event){
	image_load_arr = [false, false, false, false];
	var form = document.getElementById("submit-form");
	var submit =  document.getElementById("submit-button");
	var comment = document.getElementById("Comment").value;
	//submit.setAttribute("disabled", 1);
	form_submision_values =  new FormData();
	
	//create a ticket to prevent resubmision on 
	 var ticket_request = new XMLHttpRequest();
	 ticket_request.open("GET", "/ticket-gen?comment=" + comment);
	 ticket_request.responseType="text";
	 ticket_request.onload = function(){	 
		var ticket = ticket_request.response;
		console.log(ticket);
		if(ticket[0] == "-"){
			ticket_arr = ticket.split(" ");	
			if(ticket_arr[0] == "-1") window.alert("Don't double click submit.............\nServer got the comment, is processing image.");
			if(ticket_arr[0] == "-2") {
				var response = window.confirm("Comment will require admin verification due to text filter. Please remove the word '" + ticket_arr[1] + "' or press OK to be put on the verification list");
				if(response == true){
					document.getElementById("Comment").value = "VERIFY: " +  document.getElementById("Comment").value
					submitProcess(event);
				}
			}
			if(ticket_arr[0] == "-3") {
				alert("Comment too long");
			}
			return false;
		}

		form_submision_values.append("ticket", ticket);
		
		var file_upload_event = new Event("FileUploaded");
		for(var file = 1 ; file <= 4 ; file++){
			(function(_file){
				var image_reader = new FileReader();
				var file_obj = document.getElementById("f" + _file).files[0];
				if(file_obj !== undefined){
					image_reader.readAsBinaryString(file_obj, "UTF-8");
					image_reader.onload=function(){	}
					image_reader.onloadend=function(){
						form_submision_values.append("file" + _file, file_obj.name + " " + btoa(image_reader.result));
						console.log(file_obj.name  + " " + btoa(image_reader.result).substring(0,15));
						image_load_arr[_file - 1] = true;
						document.dispatchEvent(file_upload_event);
					}
				}
				else{
					image_load_arr[_file - 1] = true;
					form_submision_values.append("file" + _file, "");
					document.dispatchEvent(file_upload_event);
				}
			})(file);
		}
		
	 }
	 ticket_request.send(null);
	
	submit.removeAttribute("comment");
	event.preventDefault();
	return false;
}

for(var i = 1 ; i <= 4; i++) setFileListener("f" + i);

if (textarea.addEventListener) {
	textarea.addEventListener('input', function() {
		checkIfSubmitToBeDisabled();
  }, false);
} 
else if (textarea.attachEvent) {
	textarea.attachEvent('onpropertychange', function() {
		 checkIfSubmitToBeDisabled();
  });
}
document.getElementById("submit-form").addEventListener("submit", submitProcess);



