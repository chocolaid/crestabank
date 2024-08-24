var togglePassword = document.getElementById("toggle-password");
var formContent = document.getElementsByClassName('form-content')[0]; 
var getFormContentHeight = formContent.clientHeight;

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

var formImage = document.getElementsByClassName('form-image')[0];
if (formImage) {
	var setFormImageHeight = formImage.style.height = getFormContentHeight + 'px';
}


if (togglePassword) {
	togglePassword.addEventListener('click', function() {
	  var x = document.getElementById("password");
	  if (x.type === "password") {
	    x.type = "text";
	  } else {
	    x.type = "password";
	  }
	});
}
function showSnackbar(message, backgroundColor) {
	Snackbar.show({
		text: message,
		backgroundColor: backgroundColor,
		pos: 'top-center',
		duration: 3000
	});
}