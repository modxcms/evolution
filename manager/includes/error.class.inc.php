<?php
// this is the old error handler. Here for legacy, until i replace all the old errors.
class errorHandler{

var $errorcode;
var $errors = array(
	0	=> 	"No errors occured.",
	1	=>	"An error occured!",
	2	=>	"Document's ID not passed in request!",
	3	=>	"You don't have enough privileges for this action!",
	4	=>	"ID passed in request is NaN!",
	5	=>	"The document is locked!",
	6	=>	"Too many results returned from database!",
	7	=>	"Not enough/ no results returned from database!",
	8	=>	"Couldn't find parent document's name!",
	9	=>	"Logging error!",
	10	=>	"Table to optimise not found in request!",
	11	=>	"No settings found in request!",
	12	=>	"The document must have a title!",
	13	=>	"No user selected as recipient of this message!",
	14	=>	"No group selected as recipient of this message!",
	15	=>	"The document was not found!",
		
	
	
	100 =>	"Double action (GET & POST) posted!",
	600 =>	"Document cannot be it's own parent!",
	601 =>	"Document's ID not passed in request!",
	602 =>	"New parent not set in request!",
	900 =>	"Incorrect username or password entered!", // don't know the user!
	901 =>	"Incorrect username or password entered!", // wrong password!
	902 =>	"Due to too many failed logins, you have been blocked!",
	903 =>	"You are blocked and cannot log in!",
	904 =>	"You are blocked and cannot log in! Please try again later.",
	905 =>	"The security code you entered didn't validate! Please try to login again!"
	);

	function setError($errorcode, $custommessage=""){
		$this->errorcode=$errorcode;
		$this->errormessage=$this->errors[$errorcode];
		if($custommessage!="") {
			$this->errormessage=$custommessage;
		}
	}	
	
	function getError() {
		return $this->errorcode;
	}
	
	function dumpError(){
?>
	<html>
	<head>
	<title>MODx :: Error</title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $modx_manager_charset; ?>">
	<script>
		function showError(){
			alert("<?php echo $this->errormessage; ?>");
			history.back(-1);
		}
		setTimeout("showError()",10);
	</script>
	</head>
	<body>
	</body>
	</html>
<?php
		exit;
	}
}
?>