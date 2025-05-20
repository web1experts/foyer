<script>
var password = document.getElementById("pass")
  , confirm_password = document.getElementById("cpass");

function validatePassword(){
	if(confirm_password.value==""){
		document.getElementById('custom_message').innerHTML='Please fill out this field.';
	} else if(password.value != confirm_password.value) {
    	confirm_password.setCustomValidity("Passwords Don't Match");
    	document.getElementById('custom_message').innerHTML='Password and confirm password not matched';
  	} else {
    	confirm_password.setCustomValidity('');
    	document.getElementById('custom_message').innerHTML='Please fill out this field.';
  	}
}

password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;	
</script>


<script>
// Disable form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Get the forms we want to add validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>
</body>
</html>