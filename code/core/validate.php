<?php  

// Sanitize user input to prevent XSS attacks
// Uses trim() to remove whitespace, stripslashes() to remove backslashes,
// and htmlspecialchars() to convert special characters into HTML entities
function sanitizeInput($data) {

  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;

}

// Validate password strength
// Password must be more than 8 characters and contain
// at least one lowercase letter, one uppercase letter, and one number
function validatePassword($password) {

	if (strlen($password) > 8) {
		$hasLower = false;
		$hasUpper = false;
		$hasNumber = false;

	    for ($i = 0; $i < strlen($password); $i++) {

	    	if (ctype_lower($password[$i])) {
	    		$hasLower = true; 
	        } 

	        elseif (ctype_upper($password[$i])) {
	            $hasUpper = true; 
	        } 

	        elseif (ctype_digit($password[$i])) {
	            $hasNumber = true;
	        }

	        if ($hasLower && $hasUpper && $hasNumber) {
	            return true; 
	        }
	    }
	}

	else {
		return false; 
	}
}

?>
