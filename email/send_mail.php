<?php
/*
This first bit sets the email address that you want the form to be submitted to.
You will need to change this value to a valid email address that you can access.
 */
$webmaster_email = "MANAGER@farzat.co";

/*
This bit sets the URLs of the supporting pages.
If you change the names of any of the pages, you will need to change the values here.
 */
$feedback_page = "contact.html";
$error_page = "error_message.html";
$thankyou_page = "thank_you.html";

/*
This next bit loads the form field data into variables.
If you add a form field, you will need to add it here.
 */
$user_name = $_REQUEST['user_name'];
$email_address = $_REQUEST['email_address'] ;
$comments = $_REQUEST['comments'] ;


//to replace all linebreaks to <br />
function nl2br2($string) {
$string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
return $string;
}

/*
The following function checks for email injection.
Specifically, it checks for carriage returns - typically used by spammers to inject a CC list.
 */
function isInjected($str) {
	$injections = array('(\n+)',
	'(\r+)',
	'(\t+)',
	'(%0A+)',
	'(%0D+)',
	'(%08+)',
	'(%09+)'
	);
	$inject = join('|', $injections);
	$inject = "/$inject/i";
	if(preg_match($inject,$str)) {
		return true;
	}
	else {
		return false;
	}
}

// If the user tries to access this script directly, redirect them to the feedback form,
if (!isset($_REQUEST['email_address'])) {
    header( "Location: $feedback_page" );
}

// If the form fields are empty, redirect to the error page.
elseif (empty($email_address) || empty($comments) || empty($user_name)) {
    header( "Location: $error_page" );
}

// If email injection is detected, redirect to the error page.
elseif ( isInjected($email_address) ) {
    header( "Location: $error_page" );
}

// If we passed all previous tests, send the email then redirect to the thank you page.
else {
    mail( "$webmaster_email", "Email from Farzat's Homepage",
      "Name: ".$user_name."\r\n"."\r\n".$comments, "From: $email_address" );
    header( "Location: $thankyou_page" );
}
?>