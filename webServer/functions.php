<?php
include('ChromePHP.php');
session_start();
if(!isset($_SESSION['loginMessage'])) $_SESSION['loginMessage'] = "";
if(!isset($_SESSION['user'])) $_SESSION['user'] = "";
if(!isset($_SESSION['login'])) $_SESSION['login'] = "0";
if(!isset($_SESSION['color1'])) $_SESSION['color1'] = "#63c8ff";
if(!isset($_SESSION['color2'])) $_SESSION['color2'] = "#63c8ff";
// if(isset($_SESSION['loginID'])){
// 	$_SESSION['color1'] = "#63c8ff";
// 	$_SESSION['color2'] = "#63c8ff";
// 	loadSavedPreference($_SESSION['loginID']);
// }
date_default_timezone_set('America/Chicago');

if(isset($_POST['color1']) && isset($_POST['color2'])){
	updateUserPreference($_SESSION['user'], $_POST['color1'], $_POST['color2']);
}
if(isset($_POST['name']) && isset($_POST['ticket']) && isset($_POST['status']) && isset($_POST['uniqueID']) && isset($_POST['assigned'])){
	//Random number is ticket uniqueID placeholder
	createAssignment(substr($_POST['uniqueID'], 62, 6), $_POST['name'], $_POST['ticket'], $_POST['status'], $_POST['assigned']);
	header('Location: /');
}

if(isset($_POST['ticketRemove'])){
	removeAssignment($_POST['ticketRemove']);
	header('Location: '.$_SERVER['REQUEST_URI']);
}

if(isset($_POST['ticketWorking'])){
	workingAssignment($_POST['ticketWorking']);
	header('Location: '.$_SERVER['REQUEST_URI']);
}

if(isset($_POST['csvData'])){
	if(isset($_POST['option-one']))	$option = 1;
	else $option = 0;
	$csv = parse_csv(trim($_POST['csvData']));	//Trim removes the whitespace at the end of the copy/paste
	$extraEmployees = array();
	if(isset($_POST['extraEmp'])){
		$extraEmployees = explode(", ", trim($_POST['extraEmp']));
	}
	randomizeAssignments($csv, $option, $extraEmployees);
}

if(isset($_GET['logout'])){
	unset($_SESSION['login']);
	header('Location: /');
}

if(isset($_POST['username']) && isset($_POST['password'])){
	$result = authAPI("GET");
	if($result) {
		$_SESSION['loginMessage'] = "";
		$_SESSION['login'] = 1;
		header("Location: /");
	} else {
		$_SESSION['loginMessage'] = "Username or Password Incorrect.";
		header("Location: /login");
	}

}

function removeAssignment($ticketNumber){
	if($ticketNumber == -1) $sql = "DELETE FROM tickets WHERE working = 0;";
	else $sql = "DELETE FROM tickets WHERE ticketNumber=".$ticketNumber.";";
	$db = new PDO('sqlite:db/tks.db');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try
	{
		 $result0=$db->query($sql);
	}
	catch(PDOException $e)
	{
		 echo "Statement failed: " . $e->getMessage();
		 return false;
	}
}

function workingAssignment($ticketNumber){
	$sql = "UPDATE tickets set working = (case working when 1 then 0 else 1 end) where ticketNumber = ".$ticketNumber.";";
	$db = new PDO('sqlite:db/tks.db');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// 	if ($db != null) echo "<p>db connected</p>"; 
// 	else echo "<p>db did not connect</p>";
	try	{
		 $result0=$db->query($sql);
// 		 print "<p>query ran</p>";
	}
	catch(PDOException $e){
		 echo "Statement failed: " . $e->getMessage() . "<br>";
		 return false;
	}
}

function createAssignment($uniqueID, $employee, $ticketNumber, $status, $assigned){
	$working = 0; //Initialize working as 0. Will be changed with button
	$sql = "INSERT INTO tickets (uniqueID, employee, status, assignedDate, ticketNumber, working) VALUES (".$uniqueID.", '".$employee."', '".$status."', ".$assigned.", ".$ticketNumber.", ".$working.");";
	$db = new PDO('sqlite:db/tks.db');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// 	if ($db != null) echo "<p>db connected</p>"; 
// 	else echo "<p>db did not connect</p>";
	try	{
		 $result0=$db->query($sql);
// 		 print "<p>query ran</p>";
	}
	catch(PDOException $e){
		 echo "Statement failed: " . $e->getMessage() . "<br>";
		 return false;
	}
}

function readTickets(){
	$sql = "SELECT * FROM tickets";
	$db = new PDO('sqlite:db/tks.db');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// 	if ($db != null) echo "<p>db connected</p>"; 
// 	else echo "<p>db did not connect</p>";
	try	{
		 $result=$db->query($sql);
// 		 print "<p>query ran</p>";
	}
	catch(PDOException $e){
		 echo "Statement failed: " . $e->getMessage() . "<br>";
		 return false;
	}
	
	$hex1 = $_SESSION['color1'];
	list($r, $g, $b) = sscanf($hex1, "#%02x%02x%02x");
	$hex1 = "rgba($r,$g,$b,0.3)";
	
	$hex2 = $_SESSION['color2'];
	list($r, $g, $b) = sscanf($hex2, "#%02x%02x%02x");
	$hex2 = "rgba($r,$g,$b,0.3)";
	foreach($result as $row)
    {
    	if($row['working']){
//     		$style = "class=pure-table-odd";
			if($row['employee'] == $_SESSION['user']){
				$style = 'style=background:'.$hex1.';';
			}else{
				$style = 'style=background:'.$hex2.';';
			}
    		$button = "pure-button-active";
    	}
    	else{
    		$style = "";
    		$button = "";
    	} 
    	$link = "https://cytech.its.iastate.edu/CGWeb/MainUI/ServiceDesk/SDItemEditPanel.aspx?boundtable=IIncidentRequest&CloseOnPerformAction=false&ID=".$row['uniqueID']."&IsStandAlone=true";	
		print "<tr ".$style."><td>".$row['employee']."</td>";
		print "<td>IR-0".$row['ticketNumber']."</td>";
		print "<td>".$row['status']."</td>";
		print "<td>".date('m-d-y g:i A', $row['assignedDate'])."</td>";
		print "<td><button onclick='working(".$row['ticketNumber'].")' class='pure-button ".$button."'>Working</button>&nbsp;<button onclick='remove(".$row['ticketNumber'].")' class=pure-button>Remove</button></td>";
		print "<td><a target='_blank' href=".$link." class=pure-button>Open</a>";
// 		print "<td>".$row['working']."</td>";
		echo "</tr>";
		}
}

function printHeader(){
$logout = "<i class='fas fa-sign-out-alt'></i>";
$login = "<i class='fas fa-sign-in-alt'></i>";

if($_SESSION['login'] == "0"){
	$state = "<a href='login.php' class='pure-menu-link'>".$login." Login";
} else {
	$state = "<a href='logout.php' class='pure-menu-link'>".$logout." Logout";
}
if("index.php" == basename($_SERVER['PHP_SELF'])){
	$refresh = "<meta http-equiv='refresh' content='30'>";
} else {
	$refresh = "";
}

echo "<!doctype html>
<html>
  <head>
    <title>SC Tickets</title>
    <script language='JavaScript' type='text/javascript' src='js/index.js'></script>
    <script language='JavaScript' type='text/javascript' src='js/jquery.js'></script>
    <link rel='stylesheet' href='https://unpkg.com/purecss@1.0.0/build/pure-min.css' integrity='sha384-nn4HPE8lTHyVtfCBi5yW9d20FjT8BJwUXyWZT9InLYax14RDjBj46LmSztkmNP9w' crossorigin='anonymous'>
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.4.2/css/all.css' integrity='sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns' crossorigin='anonymous'>
  	<link rel='icon' type='image/png' href='/favicon.png'>
    <link rel='stylesheet' href='css/index.css'>
    <meta charset='UTF-8'>
	<meta name='google' content='notranslate'>
	<meta http-equiv='Content-Language' content='en'>
	".$refresh."
  </head>
  <body>
	<div class='pure-g'>
	<div class='pure-u-1'>
	<div class='pure-menu pure-menu-horizontal'>
	<img src='isu.png' style='height: 50px; position:relative; top:10px; left:10px; z-index: -1'> 
		<ul class='pure-menu-list' style='left:10px;'>
			<li class='pure-menu-item'><a href='/' class='pure-menu-link'><i class='fas fa-home'></i> Home</a></li>
			<li class='pure-menu-item'><a href='assign.php' class='pure-menu-link'><i class='fas fa-clipboard-list'></i> Assignments</a></li>
			<li class='pure-menu-item'>".$state."</a></li>
		</ul> &nbsp;&nbsp;&nbsp;&nbsp; ".$_SESSION['user']."
	</div><hr>";
}

function parse_csv($csv_string, $delimiter = ",", $skip_empty_lines = true, $trim_fields = true)
{
    $enc = preg_replace('/(?<!")""/', '!!Q!!', $csv_string);
    $enc = preg_replace_callback(
        '/"(.*?)"/s',
        function ($field) {
            return urlencode(utf8_encode($field[1]));
        },
        $enc
    );
    $lines = preg_split($skip_empty_lines ? ($trim_fields ? '/( *\R)+/s' : '/\R+/s') : '/\R/s', $enc);
    return array_map(
        function ($line) use ($delimiter, $trim_fields) {
            $fields = $trim_fields ? array_map('trim', explode($delimiter, $line)) : explode($delimiter, $line);
            return array_map(
                function ($field) {
                    return str_replace('!!Q!!', '"', utf8_decode(urldecode($field)));
                },
                $fields
            );
        },
        $lines
    );
}

function getEmployees($option, $appointments){
	// 	Finesse States = 'READY' 'TALKING' 'NOT_READY' 'WORK' 'LOGOUT'
	$users = CallAPI("GET");
	$xml = simplexml_load_string($users);
	$empList = array();
	foreach($xml->User as $single){
		if(($single->teamName == '44000 Solution Center') && ($single->state != 'LOGOUT')){
			$name = $single->firstName." ".$single->lastName;
			if($name == "Vincent Gregory") continue;
			if($name == "Brent Black") continue;
			if($name == "Megan Jensen") continue;
			if($name == "Linda DeSchane") continue;
			if(strpos($name, 'Tier 5') !== false && $option == 0) continue; //Note that the use of '!==' false is deliberate to see if string is contained
			if(strpos($name, 'Tier 0') !== false) continue; 				//Option 0 is checkbox unchecked
			if(strpos($name, 'Tier 1') !== false) continue;					// T0 - T2 are removed from doing tickets as they should be doing unassigned.
			if(strpos($name, 'Tier 2') !== false) continue;
			// foreach($appointments as $new){
			// 	array_push($empList, $new);
			// }
			array_push($empList, $name);
		}
	}

	if(count($appointments) <= 1){
		return $empList;
	} else {
		return array_merge($empList, $appointments);
	}
}

// Doesnt check against already signed in accounts so if youre signed into t5 your t0 will show logged out and get added.
// Not that bad, but could be fixed in V2.
// Isnt used anymore. Should be removed.
function getOfflineEmployees(){ 
	$users = CallAPI("GET");
	$xml = simplexml_load_string($users);
	$empList = array();
	$itr = 0;
	foreach($xml->User as $single){	
		$name = substr($single->firstName." ".$single->lastName, 9); // Removes "Tier # - "
		$fullname = $single->firstName." ".$single->lastName;
		if(($single->teamName == '44000 Solution Center') && ($single->state == 'LOGOUT')){
			if($single->firstName." ".$single->lastName == "Vincent Gregory") continue;
			if($single->firstName." ".$single->lastName == "Brent Black") continue;
			if($single->firstName." ".$single->lastName == "Megan Jensen") continue;
			if($single->firstName." ".$single->lastName == "Linda DeSchane") continue;
			if($single->firstName." ".$single->lastName == "Meghna Vaidya") continue; 
			// Not a CSA but her phone was really messed up during onboarding.
			if(strcasecmp(substr($name, 0, 2), "sc") == 0) continue; //This is some crap to remove phone logins that were made incorrectly
			if(!strcasecmp(substr($single->firstName, 0, 2), "sc")) continue; //Same as above
			$checkbox = "<label for='option-one' class='pure-checkbox'><input id='option-one' type='checkbox' value='".$name."' name='appname-".$itr."'> ".$name."</label>";
			array_push($empList, $checkbox);
		}
		$itr++;
	}
	$empList = array_unique($empList);
	foreach($empList as $x){
		echo $x;
	}
}

function CallAPI($method, $data = false)
{
	$url = "https://uc-ccx-pub.tele.iastate.edu:8445/finesse/api/Users/";
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "sc48t5:489336");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}

function randomizeAssignments($tickets, $option, $extraEmployees){
	$employees = getEmployees($option, $extraEmployees);
	$itr = 0;
	unset($tickets[0]); //Removes column headers from csv.
	
	$assignments = array();
	$size = count($employees);
	foreach($tickets as &$tkt){
		if($itr == $size) $itr = 0;
		$set = array($employees[$itr], $tkt);
		array_push($assignments, $set);
		$itr++;
	}

	// ChromePhp::log(($assignments));
	// ChromePhp::log('Assignments');
	sendAssignments($assignments);
}

function sendAssignments($list){
	removeAssignment(-1); // BAD
	foreach($list as &$asgn){
		$uniqueID = $asgn[1][0];
		$employee = $asgn[0];
		$ticketNumber = substr($asgn[1][1], -6); //substr removes IR-0, because database wont store that.
		$status = $asgn[1][2];
		$assigned = time();
		createAssignment($uniqueID, $employee, $ticketNumber, $status, $assigned);
		echo "<script>location.replace('/');</script>";
	}
}

function loadSavedPreference($user){
	$sql = "SELECT * from preferences where user = '$user'";
	$db = new PDO('sqlite:db/tks.db');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try	{
		$result=$db->query($sql);
	}
	catch(PDOException $e){
		echo "Statement failed: " . $e->getMessage() . "<br>";
		return false;
	}
	if($result->rowCount() == 0){
		$primaryColor = '#63c8ff';
		$secondaryColor = '#63c8ff';
		createUserPreference($user, $primaryColor, $secondaryColor);
		return array('color1'=>$primaryColor, 'color2'=>$secondaryColor);
	} else {
		foreach($result as $row){
			$primaryColor = $row['primaryColor'];
			$secondaryColor = $row['secondaryColor'];
		}
		updateUserPreference($user, $primaryColor, $secondaryColor);
		return array('color1'=>$primaryColor, 'color2'=>$secondaryColor);
	}
}

function updateUserPreference($user, $color1, $color2){
	$sql = "update preferences set primaryColor = '$color1', secondaryColor = '$color2' where user = '$user';";
	$db = new PDO('sqlite:db/tks.db');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try	{
		 $result0=$db->query($sql);
		//  print "<p>query ran</p>";
	}
	catch(PDOException $e){
		echo "Statement failed: " . $e->getMessage() . "<br>";
		return false;
	}
	
	$_SESSION['color1'] = $color1;
	$_SESSION['color2'] = $color2;
	if($_SERVER['REQUEST_URI'] != '/api') header('Location: /');
}

function createUserPreference($user, $color1, $color2){
	$sql = "INSERT into preferences (user, primaryColor, secondaryColor) VALUES ('".$user."', '".$color1."', '".$color2."');";
	$db = new PDO('sqlite:db/tks.db');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	try	{
		 $result0=$db->query($sql);
	}
	catch(PDOException $e){
		// echo $e->errorInfo[1];
		// if ($e->errorInfo[1] == 19) updateUserPreference($user, $color1, $color2); //Error 19 is insert failed due to unique constraint.
		echo "Statement failed: " . $e->getMessage() . "<br>";
		return false;
	}
}


function authAPI($method, $data = false)
{

	$username = $_POST['username'];
	$password = $_POST['password'];


	// Finesse API will only reply for the signed in user, unless you are an admin.
	$url = "https://uc-ccx-pub.tele.iastate.edu:8445/finesse/api/User/$username";
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }
    // Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, $username.":".$password);
    

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    
    $result = curl_exec($curl);	
    $info = curl_getinfo($curl);
    curl_close($curl);
	
	$returnCode = $info['http_code'];
	$xml = new SimpleXMLElement($result);
	
	
	// https://developer.cisco.com/docs/finesse/#!userget-user/userget-user
	// Header response codes.
	if($returnCode != '200') return false;
	else {
		$_SESSION['user'] = $xml->firstName." ".$xml->lastName;
		$_SESSION['loginID'] = strval($xml->loginName); //Added space to convert to string.
		return true;  
	}
}



?>
