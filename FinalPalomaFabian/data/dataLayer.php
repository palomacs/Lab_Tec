<?php

	function connectionToDataBase(){
		$dbservername = "localhost";
		$dbusername = "root";
		$dbpassword = "root";
		$dbname = "lab_tec";

		// Create connection
		$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
		
		if ($conn->connect_error){
			return null;
		}
		else{
			return $conn;
		}
	}

	function attemptLogin($username){
		$conn = connectionToDataBase();

		if ($conn != null){
			$sql = "SELECT user_id, first_name, last_name, email, password FROM users WHERE username = '$username' LIMIT 1";

			$result = $conn->query($sql);

			if ($result->num_rows > 0){
				$user = $result->fetch_array(MYSQLI_ASSOC);
				$conn -> close();
				return array("status" => "USER EXISTS", "user" => $user);
			}
			else{
				$conn -> close();
				return array("status" => "USERNAME NOT FOUND");
			}
		}
		else{
			$conn -> close();
			return array("status" => "CONNECTION WITH DB WENT WRONG");
		}
	}

	function validateUser($username, $email){
		$conn = connectionToDataBase();

		if ($conn != null){
			$sql = "SELECT * FROM users WHERE email = '$email' OR username = '$username'";
			$result = $conn->query($sql);

			if ($result->num_rows > 0){
				$conn -> close();
				return array("status" => "Username already exist");
			}
			else{
				$conn -> close();
				return array("status" => "USERNAME AND EMAIL VALIDATED SUCCESSFULLY");
			}
		}
		else{
			$conn -> close();
			return array("status" => "CONNECTION WITH DB WENT WRONG");
		}
	}

	function registerUser($username, $email, $password, $fName, $lName, $degree, $userType){
		$conn = connectionToDataBase();

		if ($conn != null){
			$sql = "INSERT INTO users(user_id, first_name, last_name, username, email, password, degree_program_id, user_type_id, creation)
			VALUES ('', '$fName', '$lName', '$username', '$email', '$password', '$degree', '$userType', CURRENT_TIMESTAMP)";

	    	if (mysqli_query($conn, $sql)){
	    		$conn -> close();
				return array("status" => "USER REGISTERED SUCCESSFULLY");
			}
			else{
				return array("status" => mysqli_error($conn));
			}
		}
		else{
			$conn -> close();
			return array("status" => "CONNECTION WITH DB WENT WRONG");
		}
	}

	function getDegrees(){
		$conn = connectionToDataBase();

		$results = $conn->query("SELECT degree_program_id, degree_program FROM degree_programs");

		if ($results->num_rows > 0)
		{
			$response = array();
			// output data of each row
		    while($row = $results->fetch_assoc()){
		    	array_push($response, $row);
			}

			$conn->close();

		    return array("status" => "SUCCESS", "degrees" => $response);
		}
		else{
			return array("status" => "Conflict, Degrees not found");
		}
	}

	function whoAmI($idUsername){
		$conn = connectionToDataBase();

		$sqlUser = "SELECT user_type FROM user_types WHERE user_type_id IN (SELECT user_type_id FROM users WHERE user_id = ".$idUsername.")";

		$resultUser = $conn->query($sqlUser);

		$conn->close();

		if ($resultUser->num_rows > 0){
			$rowUser = $resultUser->fetch_array(MYSQLI_ASSOC);

	    	return array("status" => "SUCCESS", "me" => $rowUser);
		}
		else{
			return array("status" => "Conflict, User not found");
		}
	}

	function getNotifications($idUsername){
		$conn = connectionToDataBase();

		$sqlUser = "SELECT b.status, n.request_id FROM notifications n LEFT JOIN borrowed_status b ON b.status_id = n.request_status_id WHERE user_id = ".$idUsername." AND notification_Status = 0";

		$result = $conn->query($sqlUser);

		$response = array();

		$conn->close();

		if ($result->num_rows > 0){

			while($row = $result->fetch_array()){
		    	array_push($response, $row);
			}

	    	return array("status" => "SUCCESS", "notifications" => $response);
		}
		else{
			return array("status" => "Notifications not found");
		}
	}

	function readNotifications($idUsername){
		$conn = connectionToDataBase();

		$sqlUser = "UPDATE notifications SET notification_status = 1 WHERE user_id = ".$idUsername."";

		if (mysqli_query($conn, $sqlUser)){
			$conn->close();
	    	return array("status" => "SUCCESS");
		}
		else{
			return array("status" => "Notifications not read");
		}
	}

	function searchMaterial($material){
		$conn = connectionToDataBase();

		$sql = "SELECT material, available, material_id FROM catalog WHERE material LIKE '%".$material."%' ORDER BY material LIMIT 10";
		$resultMaterials = $conn->query($sql);

		$response = array();

		$conn->close();

		if ($resultMaterials->num_rows > 0){

			while($rowMaterials = $resultMaterials->fetch_array()){
		    	array_push($response, $rowMaterials);
			}

	    	return array("status" => "SUCCESS", "matches" => $response);
		}
		else{
			return array("status" => "Material not found");
		}
	}

	function borrow($idUsername, $materials, $quantities, $professor, $requestDate, $returnDate){
		$conn = connectionToDataBase();

		$sql = "INSERT INTO `borrowed`(`request_id`, `user_id`, `professor`, `start_date`, `end_date`, `status_id`) VALUES ('', '".$idUsername."', '".$professor."', '".$requestDate."','".$returnDate."','3')";

		//echo ($sql);

		if (mysqli_query($conn, $sql)){
			$last_id = $conn->insert_id;
			//echo($last_id);

			$l = count($materials);

			for ($i = 0; $i < $l; $i++){
				$m = $materials[$i];
				//echo ($m);

				$sql = "SELECT material_id, laboratory_id FROM catalog WHERE material = '".$m."' LIMIT 1";

				//echo($sql);

				$result = $conn->query($sql);

				if ($result->num_rows > 0){
					$r = $result->fetch_array(MYSQLI_ASSOC);

					$m_ID = $r["material_id"];
					$l_ID = $r["laboratory_id"];

					$sql = "INSERT INTO `cart`(`cart_id`, `material_id`, `quantity`, `request_id`, `laboratory_id`) VALUES ('', '".$m_ID."', '".$quantities[$i]."', '".$last_id."', '".$l_ID."')";

					//echo($sql);

					mysqli_query($conn, $sql);

				}
				else{
					echo ("WHY");
					return array("status" => "Material not found");
				}
			}

			$conn->close();
			return array("status" => "Request&Cart SUCCESSFULLY");
		}
		else{
			return array('status' => mysqli_error($conn));
		}
	}

	function becomeAttendant($idUsername){
		$conn = connectionToDataBase();

		$sql = "INSERT INTO `attendant_wannabe`(`attendant_id`) VALUES ('$idUsername')";

		if (mysqli_query($conn, $sql)) {
    		$conn->close();
		    return array("status" => "SUCCESS");
		}
		else{
			return array("status" => mysqli_error($conn));
		}
	}

	function getBorrowRequests($idUsername){
		$conn = connectionToDataBase();

		if ($conn != null){
			$sql = "SELECT c.quantity, c.request_id, b.end_date, b.professor, b.start_date, u.username, m.material FROM cart c LEFT JOIN catalog m ON m.material_id = c.material_id LEFT JOIN borrowed b ON b.request_id = c.request_id LEFT JOIN users u ON u.user_id = b.user_id  WHERE c.laboratory_id IN (SELECT laboratory_id FROM laboratories WHERE attendant_id = ".$idUsername." AND b.status_id = 3) ORDER BY `c`.`request_id` ASC";

			//echo($sql);

	    	$results = $conn->query($sql);

			if ($results->num_rows > 0){
				$response = array();

			    while($rows = $results->fetch_assoc()){
			    	array_push($response, $rows);
				}

				return array("status" => "SUCCESS", "requests" => $response);
			}
		}
		else{
			$conn -> close();
			return array("status" => "CONNECTION WITH DB WENT WRONG");
		}
	}

	function getTaken($idUsername){
		$conn = connectionToDataBase();

		if ($conn != null){
			$sql = "SELECT c.quantity, c.request_id, b.end_date, b.professor, b.start_date, u.username, m.material, s.status FROM cart c LEFT JOIN catalog m ON m.material_id = c.material_id LEFT JOIN borrowed b ON b.request_id = c.request_id LEFT JOIN users u ON u.user_id = b.user_id LEFT JOIN borrowed_status s ON s.status_id = b.status_id WHERE c.laboratory_id IN (SELECT laboratory_id FROM laboratories WHERE attendant_id = ".$idUsername." AND (b.status_id = 1 OR b.status_id = 2)) ORDER BY `c`.`request_id` ASC";

			//echo($sql);

	    	$results = $conn->query($sql);

			if ($results->num_rows > 0){
				$response = array();

			    while($rows = $results->fetch_assoc()){
			    	array_push($response, $rows);
				}

				return array("st" => "SUCCESS", "requests" => $response);
			}
			else{
				return array("st" => "NO TAKENS");
			}
		}
		else{
			$conn -> close();
			return array("st" => "CONNECTION WITH DB WENT WRONG");
		}
	}

	function getOrders($idUsername){
		$conn = connectionToDataBase();

		if ($conn != null){
			$sql = "SELECT c.quantity, c.request_id, b.end_date, b.professor, b.start_date, u.username, m.material, s.status FROM cart c LEFT JOIN catalog m ON m.material_id = c.material_id LEFT JOIN borrowed b ON b.request_id = c.request_id LEFT JOIN users u ON u.user_id = b.user_id LEFT JOIN borrowed_status s ON s.status_id = b.status_id WHERE b.user_id = ".$idUsername." ORDER BY `c`.`request_id` ASC";

			//echo($sql);

	    	$results = $conn->query($sql);

			if ($results->num_rows > 0){
				$response = array();

			    while($rows = $results->fetch_assoc()){
			    	array_push($response, $rows);
				}

				return array("st" => "SUCCESS", "requests" => $response);
			}
			else{
				return array("st" => "NO ORDERS");
			}
		}
		else{
			$conn -> close();
			return array("st" => "CONNECTION WITH DB WENT WRONG");
		}
	}

	function checkTaken($idUsername){
		$conn = connectionToDataBase();

		if ($conn != null){
			$sql = "SELECT DISTINCT b.request_id, b.end_date FROM cart c LEFT JOIN catalog m ON m.material_id = c.material_id LEFT JOIN borrowed b ON b.request_id = c.request_id LEFT JOIN users u ON u.user_id = b.user_id LEFT JOIN borrowed_status s ON s.status_id = b.status_id WHERE c.laboratory_id IN (SELECT laboratory_id FROM laboratories WHERE attendant_id = ".$idUsername." AND b.status_id = 1) ORDER BY `c`.`request_id` ASC";

			//echo($sql);

	    	$results = $conn->query($sql);

			if ($results->num_rows > 0){
				$response = array();

				$i = 0; 

			    while($rows = $results->fetch_assoc()){
			    	array_push($response, $rows);

			    	$rId = $response[$i]["request_id"];
					$eD = $response[$i]["end_date"];
					$currentDate = date("Y-m-d H:i:s");

					//var_dump($currentDate > $eD);

					if($currentDate > $eD){
						$sql = "UPDATE borrowed SET status_id = 2 WHERE request_id = ".$rId."";
						mysqli_query($conn, $sql);
					}

					$i++;
				}
				return array("st" => "SUCCESS", "requests" => $response);
			}
			else{
				return array("st" => "NO TAKENS, NO CHECKS");
			}
		}
		else{
			$conn -> close();
			return array("st" => "CONNECTION WITH DB WENT WRONG");
		}
	}

	function manageRequestsBorrowed($id, $status){
		$conn = connectionToDataBase();

		if ($conn != null){
			$sql = "UPDATE `borrowed` SET status_id = '$status' WHERE request_id = '$id'";

			pushNotification($id, $status);

			if (mysqli_query($conn, $sql))  {
				if ($status == 1){
					$sql = "SELECT m.available, m.material_id, c.quantity FROM catalog m LEFT JOIN cart c ON m.material_id = c.material_id WHERE c.request_id = '$id'";

					$result = $conn->query($sql);

					if ($result->num_rows > 0){
						$r = array();
						$i = 0;

						 while($rows = $result->fetch_assoc()){
					    	array_push($r, $rows);

						    $mId = $r[$i]["material_id"];
							$av = $r[$i]["available"];
							$quant = $r[$i]["quantity"];
							$newAv = $av - $quant;

							$sql = "UPDATE `catalog` SET available = '$newAv' WHERE material_id = '$mId'";
							mysqli_query($conn, $sql);
							$i++;
						}
						return array("status" => "SUCCESS", "requests" => $r);
					}

				}

	    		$conn->close();
			    return array("status" => "SUCCESS");
			}
			else{
				return array("status" => mysqli_error($conn));
			}
		}
		else{
			$conn -> close();
			return array("status" => "CONNECTION WITH DB WENT WRONG");
		}
	}

	function manageRequestsTaken($id){
		$conn = connectionToDataBase();

		if ($conn != null){
			$sql = "UPDATE `borrowed` SET status_id = 5 WHERE request_id = '$id'";

			if (mysqli_query($conn, $sql))  {
				$sql = "SELECT m.available, m.material_id, c.quantity FROM catalog m LEFT JOIN cart c ON m.material_id = c.material_id WHERE c.request_id = '$id'";

				$result = $conn->query($sql);

				if ($result->num_rows > 0){
					$r = array();
					$i = 0; 

				    while($rows = $result->fetch_assoc()){
				    	array_push($r, $rows);

					    $mId = $r[$i]["material_id"];
						$av = $r[$i]["available"];
						$quant = $r[$i]["quantity"];
						$newAv = $av + $quant;

						$sql = "UPDATE `catalog` SET available = '$newAv' WHERE material_id = '$mId'";

						mysqli_query($conn, $sql);

						$i++;
					}

					return array("status" => "SUCCESS", "requests" => $r);
				}

	    		$conn->close();
			    return array("status" => "SUCCESS");
			}
			else{
				return array("status" => mysqli_error($conn));
			}
		}
		else{
			$conn -> close();
			return array("status" => "CONNECTION WITH DB WENT WRONG");
		}
	}

	function loadCatalog(){
		$conn = connectionToDataBase();

		$sql = "SELECT * FROM catalog";
		$result = $conn ->query($sql);
		if($result->num_rows>0){
			$conn -> close();
			while($row = $result->fetch_assoc()){
				$catalog[]=$row;
			}
			return array("status" => "SUCCESS","catalog" => $catalog);
		}
		else{
			return array("status"=>"NO ITEMS ON CATALOG FOR USERS");
		}
	}

	function loadCatalogAdmin($idUsername){
		$conn = connectionToDataBase();

		$sql = "SELECT catalog.material, catalog.total, catalog.available, catalog.additional_info, laboratories.laboratory_location, material_types.material_type FROM catalog LEFT JOIN laboratories ON catalog.laboratory_id = laboratories.laboratory_id LEFT JOIN material_types ON catalog.material_type_id = material_types.material_type_id WHERE catalog.laboratory_id IN (SELECT laboratory_id FROM laboratories WHERE attendant_Id = '$idUsername');";

		$result = $conn ->query($sql);
		
		if($result->num_rows>0){
			$conn -> close();
			while($row = $result->fetch_assoc()){
				$catalog[]=$row;
			}
			return array("status" => "SUCCESS","catalog" => $catalog);
		}
		else{
			return array("status"=>"NO ITEMS ON CATALOG FOR ADMINS");
		}
	}

	function sendRequest($idUser,$mName,$minQty,$lab,$adInfo){
		$conn = connectionToDataBase();
		
		$sql = "INSERT INTO `requests`(`request_id`,`material`,`quantity`,`user_id`,`laboratory_id`,`date`,`status_id`,`additional_information`) VALUES ('', '".$mName."','".$minQty."','".$idUser."','".$lab."', CURRENT_TIMESTAMP,'1','".$adInfo."')";

		//print_r($sql);

		if(mysqli_query($conn,$sql)){
			$conn -> close();
			return array("status" => "SUCCESS");
		}
		else{
			return array("status" => mysqli_error($conn));
		}
	}

	function getLabs(){
		$conn = connectionToDataBase();
		$sql = "SELECT laboratory_id, laboratory_location FROM laboratories";
		$result = $conn ->query($sql);
		if($result->num_rows>0){
			$conn -> close();
			while($row = $result->fetch_assoc()){
				$labs[]=$row;
			}
			//echo ($labs);
			return array("status" => "SUCCESS", "labs" => $labs);
		}
		else{
			return array("status" => "ERROR LOADING LABORATORIES");
		}
	}

	function updateQty($componentIds, $componentQty, $componentAv){
		$conn = connectionToDataBase();

		$l = count($componentIds);
		
		for($i=0;$i<$l;$i++){
			$id=$componentIds[$i];
			$qty=$componentQty[$i];
			$av = $componentAv[$i];
			$sql = "UPDATE catalog SET total = '$qty', available = '$av' WHERE material = '".$id."'";
			//var_dump($sql);
			mysqli_query($conn,$sql); 
		}
		return array("status" => "SUCCESS");
	}

	function pushNotification($request_id, $status_id){
		$conn = connectionToDataBase();

		$sql = "SELECT user_id FROM borrowed WHERE request_id = ".$request_id."";

		$result = $conn->query($sql);

		if ($result->num_rows > 0){
				$r = $result->fetch_array(MYSQLI_ASSOC);

				$user_id = $r["user_id"];

				$sql = "INSERT INTO `notifications`(`notification_id`, `user_id`, `request_status_id`, `notification_status`, `request_id`) VALUES ('','$user_id','$status_id','0', '$request_id')";

				//echo ($sql);

				if(mysqli_query($conn,$sql)){
					//echo ("HURRAY");
				}
		}
	}

	function getComponent(){
		$conn = connectionToDataBase();
		$sql = "SELECT * FROM material_types";
		$result = $conn->query($sql);
		if($result->num_rows>0){
			while($row=$result->fetch_assoc()){
				$comp[]=$row;
			}
			return array("status"=>"SUCCESS","component"=>$comp);
		}
		else{
			return array("status"=>"ERROR TRYING TO GET MATERIAL");
		}
	}

	function addComponent($id,$name,$type,$availability,$info){
		$conn = connectionToDataBase();
		$sql = "SELECT laboratory_id FROM laboratories WHERE attendant_id='$id' LIMIT 1";
		$result = $conn->query($sql);

		if($result->num_rows>0){
			$r = $result->fetch_array(MYSQLI_ASSOC);
			$labid = $r["laboratory_id"];
			//echo($labid);
		}
		$sql = "INSERT INTO `catalog`(`material`,`material_type_id`,`total`,`available`,`additional_info`,`laboratory_id`) VALUES ('".$name."','".$type."','".$availability."','".$availability."','".$info."','".$labid."')";

		//echo($sql);

		if(mysqli_query($conn,$sql)){
			$conn -> close();
			return array("status"=>"SUCCESS");
		}
		else{
			return array("status"=>"ERROR TRYING TO REGISTER COMPONENT");
		}
	}
?>