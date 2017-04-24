<?php

header('Content-type: application/json');
require_once __DIR__ . '/dataLayer.php';

$action = $_POST["action"];

switch($action){
	case "LOGIN" : loginFunction();
		break;
	case "LOGIN_SESSION" : loginSessionFunction();
		break;
	case "REGISTRATION" : registrationFunction();
		break;
	case "GET_DEGREES" : getDegreesFunction();
		break; 
	case "LOGOUT" : logoutFunction();
		break;
	case "WHO_AM_I": whoAmIFunction();
		break;
	case "SEARCH": searchFunction();
		break;
	case "BORROW" : borrowFunction();
		break;
	case "BECOME_ATTENDANT" : becomeAttendantFunction();
		break;
	case "GET_BORROW_REQUESTS" : getBorrowRequestsFunction();
		break;
	case "GET_TAKEN" : getTakenFunction();
		break;
	case "GET_ORDERS" : getOrdersFunction();
		break;
	case "CHECK_TAKEN" : checkTakenFunction();
		break;
	case "MANAGE_REQUESTS_BORROWED" : manageRequestsBorrowedFunction();
		break;
	case "MANAGE_REQUESTS_TAKEN" : manageRequestsTakenFunction();
		break;
	case "LOADCAT" : loadCatalogFunctionAdmin();
		break;
	case "SEND_REQUEST" : sendRequestFunction();
		break;
	case "GETLABS" : loadLabsFunction();
		break;
	case "UPDATE_QTY" : updateQtyFunction();
		break;
	case "NOTIFICATIONS": notificationsFunction();
		break;
	case "READ_NOTIFICATIONS": readNotificationsFunction();
		break;
	case "GET_CT" : getComponentType();
		break;
	case "ADDCOMP" : addComponentFunction();
		break;
	case 'LOAD_CATALOG': loadCatalogFunction();
		break;
	default:break;
}

function loadCatalogFunction(){
	session_start();
	if(isset($_SESSION['user_id'])){
		$result = loadCatalog();
		if($result["status"]=="SUCCESS"){
			echo json_encode($result['catalog']);
		}
		else{
			header('HTTP/1.1 500' . $result["status"]);
			die($result["status"]);
		}
	}
	else{
		header('HTTP/1.1 500' . $result["status"]);
		die($result["status"]);
	}
}

#Action to decrypt the password of the user
function decryptPassword($password)
{
	$key = pack('H*', "bcb04b7e103a05afe34763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
    
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
	
    $ciphertext_dec = base64_decode($password);
    $iv_dec = substr($ciphertext_dec, 0, $iv_size);
    $ciphertext_dec = substr($ciphertext_dec, $iv_size);

    $password = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
   	
   	
   	$count = 0;
   	$length = strlen($password);

    for ($i = $length - 1; $i >= 0; $i --)
    {
    	if (ord($password{$i}) === 0)
    	{
    		$count ++;
    	}
    }

    $password = substr($password, 0,  $length - $count); 

    return $password;
}

# Action to encrypt the password of the user
function encryptPassword()
{
	$userPassword = $_POST['password'];

    $key = pack('H*', "bcb04b7e103a05afe34763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
    $key_size =  strlen($key);
    
    $plaintext = $userPassword;

    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    
    $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $plaintext, MCRYPT_MODE_CBC, $iv);
    $ciphertext = $iv . $ciphertext;
    
    $userPassword = base64_encode($ciphertext);

    return $userPassword;
}

function loginFunction(){
	session_start();

	$username = $_POST['username'];
	$password = $_POST['password'];

	if (isset($_POST['remember'])) {
		$month = time() + (60 * 60 * 24 * 30);
		setcookie('remember_user', $username, $month,"/");

	}
	else{
		//$past = time() - 100;
		//setcookie(remember_user, gone, $past);

		unset($_COOKIE["remember_user"]); 
		// empty value and expiration one hour before 
		setcookie("remember_user", '', time() - 3600, "/");
		
	}

	$result = attemptLogin($username);
	$pass = $result["user"];

	$decryptedPassword = decryptPassword($pass['password']);

	if ($result["status"] == "USER EXISTS" && $decryptedPassword == $password){
		$user = $result["user"];
		$_SESSION['user_id'] = $user['user_id']; 
		
		echo json_encode(array("message" => "Login Successful"));
	}	
	else{
		header('HTTP/1.1 500' . $result["status"] . "PASSWORD INCORRECT");
		die($result["status"]. " PASSWORD INCORRECT");
	}	
}

function loginSessionFunction(){
	session_start();

	if (isset($_SESSION['user_id'])) {
		echo json_encode($_SESSION['user_id']);
	}
}

function registrationFunction(){
	$username = $_POST['username'];
	$email = $_POST['email'];

	$result = validateUser($username, $email);

	if ($result["status"] == "USERNAME AND EMAIL VALIDATED SUCCESSFULLY"){
		$password = encryptPassword();
		$fName = $_POST['fName'];
		$lName = $_POST['lName'];
		$degree = $_POST['degree'];
		$userType = $_POST['userType'];

		$result = registerUser($username, $email, $password, $fName, $lName, $degree, $userType);

		if ($result["status"] == "USER REGISTERED SUCCESSFULLY"){
			$_SESSION['user_id'] = $conn->insert_id;
			loginFunction();
		}
		else{
			header('HTTP/1.1 500' . $result["status"]);
			die($result["status"]);
		}
	}
	else{
		header('HTTP/1.1 409' . $result["status"]);
		die($result["status"]);
	}
}

function getDegreesFunction(){
	$result = getDegrees();

	if ($result["status"] != "SUCCESS"){
		header('HTTP/1.1 500' . $result["status"]);
		die($result["status"]);
	}
	else{
		echo json_encode($result["degrees"]);
	}
}

function getBorrowRequestsFunction(){
	session_start();

	if (isset($_SESSION['user_id'])) {
		$idUsername = $_SESSION['user_id'];
		
		$result = getBorrowRequests($idUsername);

		if ($result["status"] == "SUCCESS"){
			echo json_encode($result["requests"]);
		}
		else{
			header('HTTP/1.1 500' . $result["status"]);
			die($result["status"]);
		}

	}
	else{
		header('HTTP/1.1 500' . $result["status"]);
		die($result["status"]);
	}
}

function getTakenFunction(){
	session_start();

	if (isset($_SESSION['user_id'])) {
		$idUsername = $_SESSION['user_id'];
		
		$result = getTaken($idUsername);

		if ($result["st"] == "SUCCESS"){
			echo json_encode($result["requests"]);
		}
		else{
			header('HTTP/1.1 500' . $result["st"]);
			die($result["st"]);
		}

	}
	else{
		header('HTTP/1.1 409' . $result["status"]);
		die($result["status"]);
	}
}

function getOrdersFunction(){
	session_start();

	if (isset($_SESSION['user_id'])) {
		$idUsername = $_SESSION['user_id'];
		
		$result = getOrders($idUsername);

		if ($result["st"] == "SUCCESS"){
			echo json_encode($result["requests"]);
		}
		else{
			header('HTTP/1.1 500' . $result["st"]);
			die($result["st"]);
		}

	}
	else{
		header('HTTP/1.1 409' . $result["status"]);
		die($result["status"]);
	}
}

function checkTakenFunction(){
	session_start();

	if (isset($_SESSION['user_id'])) {
		$idUsername = $_SESSION['user_id'];
		
		$result = checkTaken($idUsername);

		if ($result["st"] == "SUCCESS"){
			echo json_encode($result["requests"]);
		}
		else{
			header('HTTP/1.1 500' . $result["st"]);
			die($result["st"]);
		}

	}
	else{
		header('HTTP/1.1 409' . $result["st"]);
		die($result["st"]);
	}
}

function whoAmIFunction(){
	session_start();

	if(isset($_SESSION['user_id'])) {
		$idUsername = $_SESSION['user_id'];
		$result = whoAmI($idUsername);

		$s = array();
		$s = $result;
		//echo ($s['user_type']);

		if($result["status"] == "SUCCESS"){
			echo json_encode($s);
		}
		else{
			header('HTTP/1.1 409' . $result["status"]);
			die($result["status"]);
		}
	}
	else{
		header('HTTP/1.1 401' . $result["status"]);
		die($result["status"]);
	}
}

function borrowFunction(){
	session_start();

	if(isset($_SESSION['user_id'])) {
		$idUsername = $_SESSION['user_id'];
		$materials = $_POST['materials'];
		$quantities = $_POST ['quantities'];
		$professor = $_POST['professor'];
		$requestDate = $_POST['requestDate'];
		$returnDate = $_POST['returnDate'];

		$result = borrow($idUsername, $materials, $quantities, $professor, $requestDate, $returnDate);

		if($result["status"] == "Request&Cart SUCCESSFULLY"){
			echo json_encode(array("message" => "Cart created successfully"));
		}
		else{
			header('HTTP/1.1 409' . $result["status"]);
			die($result["status"]);
		}
	}
	else{
		header('HTTP/1.1 401' . $result["status"]);
		die($result["status"]);
	}
}

function logoutFunction(){
	session_start();
	session_unset();
	session_destroy();
	echo json_encode("Logout Successful");
}

function searchFunction(){
	session_start();

	if(isset($_SESSION['user_id'])) {
		$material = $_POST['material'];

		if($material != ""){
			$result = searchMaterial($material);

			if($result["status"] == "SUCCESS"){
				echo json_encode($result["matches"]);
			}
			else{
				header('HTTP/1.1 409' . $result["status"]);
				die($result["status"]);
				//echo($result["status"]);
			}
		}
	}
	else{
		header('HTTP/1.1 401' . $result["status"]);
		die($result["status"]);
	}
}

function becomeAttendantFunction(){
	session_start();

	if(isset($_SESSION['user_id'])) {
		$idUsername = $_SESSION['user_id'];

		$result = becomeAttendant($idUsername);

		if($result["status"] == "SUCCESS"){
			echo json_encode(array("message" => "Request successful"));
		}
		else{
			header('HTTP/1.1 409' . $result["status"]);
			die($result["status"]);
		}
	}
	else{
		header('HTTP/1.1 401' . $result["status"]);
		die($result["status"]);
	}
}

function manageRequestsBorrowedFunction(){
	session_start();

	if (isset($_SESSION['user_id'])) {
		$result = manageRequestsBorrowed($_POST['id'], $_POST['status']);

		if ($result["status"] == "SUCCESS"){
			echo json_encode(array("message" => "Request modified"));
		}
		else{
			header('HTTP/1.1 500' . $result["status"]);
			die($result["status"]);
		}
	}
	else{
		header('HTTP/1.1 500' . $result["status"]);
		die($result["status"]);
	}
}

function manageRequestsTakenFunction(){
	session_start();

	if (isset($_SESSION['user_id'])) {
		$result = manageRequestsTaken($_POST['id']);

		if ($result["status"] == "SUCCESS"){
			echo json_encode(array("message" => "Request modified"));
		}
		else{
			header('HTTP/1.1 409' . $result["status"]);
			die($result["status"]);
		}
	}
	else{
		header('HTTP/1.1 500' . $result["status"]);
		die($result["status"]);
	}
}

function loadCatalogFunctionAdmin(){
	session_start();
	if(isset($_SESSION['user_id'])){
		$idUsername = $_SESSION['user_id'];
		$result = loadCatalogAdmin($idUsername);

		if($result["status"]=="SUCCESS"){
			echo json_encode($result['catalog']);
		}
		else{
			header('HTTP/1.1 500' . $result["status"]);
			die($result["status"]);
		}
	}
	else{
		header('HTTP/1.1 500' . $result["status"]);
		die($result["status"]);
	}
}

function sendRequestFunction(){
	session_start();
	if(isset($_SESSION['user_id'])){
		$idUser = $_SESSION['user_id'];
		$mName = $_POST['materialName'];
		$minQty = $_POST['materialQuantity'];
		$lab = $_POST['laboratory'];
		$adInfo = $_POST['additionalInfo'];
		$result = sendRequest($idUser,$mName,$minQty,$lab,$adInfo);
		if($result['status']=="SUCCESS"){
			echo json_encode($result["status"]);
		}
		else{
			header('HTTP/1.1 500' . $result["status"]);
			die($result["status"]);
		}
	}
	else{
		header('HTTP/1.1 500' . $result["status"]);
		die($result["status"]);
	}
}

function loadLabsFunction(){
	session_start();
	if(isset($_SESSION['user_id'])){
		$result=getLabs();
		if($result['status']=="SUCCESS"){
			echo json_encode($result['labs']);
		}
		else{
			header('HTTP/1.1 500' . $result["status"]);
			die($result["status"]);
		}
	}
	else{
		header('HTTP/1.1 500' . $result["status"]);
		die($result["status"]);
	}
}

function updateQtyFunction(){
	session_start();

	if (isset($_SESSION['user_id'])) {
		$componentIds = $_POST['compID'];
		$componentQty = $_POST['compQty'];
		$componentAv = $_POST['compAv'];
		$result = updateQty($componentIds,$componentQty, $componentAv);
		if($result['status']=="SUCCESS"){
			echo json_encode($result['status']);
		}
		else{
			header('HTTP/1.1 500' . $result["status"]);
			die($result["status"]);
		}
	}
	else{
		header('HTTP/1.1 500' . $result["status"]);
		die($result["status"]);
	}
}

function notificationsFunction(){
	session_start();

	if (isset($_SESSION['user_id'])) {
		$idUser = $_SESSION['user_id'];

		$result = getNotifications($idUser);
		if($result['status']=="SUCCESS"){
			echo json_encode($result['notifications']);
		}
	}
	else{
		header('HTTP/1.1 500' . $result["status"]);
		die($result["status"]);
	}
}

function readNotificationsFunction(){
	session_start();

	if (isset($_SESSION['user_id'])) {
		$idUser = $_SESSION['user_id'];

		$result = readNotifications($idUser);
		if($result['status']=="SUCCESS"){
			echo json_encode($result['notifications']);
		}
		else{
			header('HTTP/1.1 500' . $result["status"]);
			die($result["status"]);
		}
	}
	else{
		header('HTTP/1.1 500' . $result["status"]);
		die($result["status"]);
	}
}

function getComponentType(){
	session_start();
	if(isset($_SESSION['user_id'])){
		$result = getComponent();
		if($result['status']=="SUCCESS"){
			echo json_encode($result['component']);
		}
		else{
			header('HTTP/1.1 500' . $result["status"]);
			die($result["status"]);
		}
	}
	else{
		header('HTTP/1.1 500' . $result["status"]);
		die($result["status"]);
	}	
}

function addComponentFunction(){
	session_start();
	if(isset($_SESSION['user_id'])){
		$iduser = $_SESSION['user_id'];
		//var_dump($iduser);
		$result = addComponent($iduser,$_POST['cName'],$_POST['cType'],$_POST['cAvailability'],$_POST['adInfo']);
		if($result['status']=="SUCCESS"){
			echo json_encode($result['status']);
		}
		else{
			header('HTTP/1.1 500' . $result["status"]);
			die($result["status"]);
		}
	}
	else{
		header('HTTP/1.1 500' . $result["status"]);
		die($result["status"]);
	}
}

?>