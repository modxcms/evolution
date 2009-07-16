// Email / Contact Form
// Simple XHTML validating email form, that sends different subjects and messages. 
// people in a company.
//
// Version 1.0
// September 9, 2005
// ryan@vertexworks.com
//

// DO NOT ALTER THE FOLLOWING TWO LINES
$subject_array = array();
$recipient_array = array();

///////////////////////////////////
//  <-----  BEGIN CONFIG  ----->
///////////////////////////////////
// Edit only what's between the quotation marks in the lines below.
// These will be the subjects that your users can choose from popup
// lists. You can have as many as you want. Each one must be set up like so:
// $subject_array[] = "What You Want This Choice To Be";
// Make sure to remove empty ones that you aren't using. Just delete the entire line.

// Generic email to use for all parts. You can edit
// the individual instances for more control.
// Defaults to the built-in email notification account which is set in the System Configuration.
// Can be set by using as follows:
// [[ContactForm? &sendTo=`ryan@vertexworks.com`]]
$email = (isset($sendTo))? $sendTo : '[(emailsender)]';


// enter "static" in order to use the static subject line
$subject_type = "static";
$static_subject = "[Web Inquiry] ".$modx->config['site_url'];

// Otherwise use an array of possible subjects
$subject_array[] = "Survey Info";
$subject_array[] = "Company Info";
$subject_array[] = "Other Info";

// Recipient ... add or remove lines as needed
// Format (as few or as many as desired): 
// $recipient_array["Your Text Here"] = 'someone@someplace.com';
$recipient_array["General Inquiries"] = "$email";
$recipient_array["Press or Interview Request"] = "$email";
$recipient_array["Partnering Opportunities"] = "$email";

// enter "static" in order to use the solo recipient
$recipient_type = "";
$static_recipient = "$email";

// Instructions 
$instructions = "Please select the type of message you'd like to send so we can route it properly. All fields are required.";

// Success Message
$success = "Thanks for contacting [(site_url)]. Someone will get back to you soon. You may submit another message in the form below.";

// Class for containing Success Message <p>
$successClass = "message";

// Failure <p> class
$failClass = "error";

// Empy Field failure message
$emptyFields = "One of the fields was left blank. Please put something in all fields.";

// General failure message
$generalFail = "Sorry, there was an error! Please try again later.";

// Bad email failure message
$failedEmail= (isset($_POST['email']))? $_POST['email']: '';
$emailFail = "The email address you supplied ({}) does not appear to be valid. Please try again.";

// Debug mode for testing
$debug = false;

//  <-----  END CONFIG  ----->
///////////////////////////////////
$SendMail = '';
if ($debug && $_POST) {
	$SendMail .= "POST variables from Document ID [*id*]:\n";
	foreach ($_POST as $key => $value) {
		$SendMail .= "\t$key => $value\n";
	}
}

$from= '';
$from_email= '';
$message= '';

$postSend= isset($_POST['send'])? $_POST['send']: 'false';
if ($postSend == 'true') { 
    $to = ($recipient_type=="static") ? $static_recipient : $_POST['to'];
    $from = $_POST['name'];
    $from_email = $_POST['email'];
    $the_subject = ($subject_type=="static") ? "$static_subject" : $_POST['subject'];
    $message = $_POST['message'];
    if ( ($from == '')||($from_email == '')||($message == '') ) {
        $SendMail .= "<p class=\"$failClass\">$emptyFields</p>";
    } elseif (eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $from_email)) {
        $subject = $the_subject;
        $headers = "From: $from <$from_email>\r\n";
        
        // clean out potential tomfoolery...
        $message = $modx->stripTags($message);
        
        $body = "Name: $from\nEmail: $from_email\nMessage:\n\n" . $message;
        if (mail($to, $subject, $body, $headers)) {
            $SendMail .= "<p class=\"$successClass\">$success</p>";
            $SendMail .= ($debug) ? "<p>$to\n$headers\n$subject\n$body</p>" : '';
            $from="";
            $from_email="";
            $message="";
        } else {
            $SendMail .= "<p class='$failClass'>$generalFail</p>";
            $send = "false";
        }
    } else {
        $SendMail .= "<p class=\"$failClass\">$emailFail</p>";
        $send = "false";
    } 
} else {
    $SendMail .= "<p>$instructions</p>";
}
$SendMail .=<<<EOD
<div class="emailform">



    <form method="post" name="EmailForm" id="EmailForm" action="[~[*id*]~]" >
        <fieldset>
            <h3>[(sitename)] Contact Form</h3>
            <input type="hidden" name="send" value="true" />
            <label for="name">Your Name: <input type="text" name="name" id="name" size="30" value="$from" /></label>

            <label for="email">Your Email Address: <input type="text" name="email" id="email" size="30" value="$from_email" /></label>

            <label for="to">Regarding:</label> 
            <select name="to" id="to">
EOD;

        foreach ($recipient_array as $key=>$value) {
            $SendMail .= "<option value=\"{$value}\">{$key}</option>\n";
        }

$SendMail .=<<<EOD
            </select>

            <label for="message">Message: 
            <textarea cols="50" rows="10" name="message" id="message">$message</textarea>
            </label>
            
            <label>&nbsp;</label><input type="submit" value="Send this Message" class="button" />
        </fieldset>
    </form>
</div>
EOD;

return $SendMail;
