<?php
error_reporting(0);
include 'db_connect.php';

class FunctionHelper {
	
	function Login($tag,$mobile,$password) {
		$response = array (
				"tag" => $tag,
				"status" => 'error'
		);
		$dbconn = new DbConnect();
		$db = $dbconn->connect();
		$tablename = "mt_user";
		$pwd = md5($password);
		$otp = mt_rand(1000,9999);
		if(isset($mobile) && isset($password)){
			$existUn = "SELECT * FROM ".$tablename." WHERE username = '".$mobile."'";
			$resultUn = mysqli_query($db,$existUn);
		
			// Associative array
			$rowUn = mysqli_fetch_assoc($resultUn);
			
			if ($rowUn > 0){
				$existUser = "SELECT * FROM ".$tablename." WHERE username = '".$mobile."' AND password = '".$pwd."'";
				$resultUser = mysqli_query($db,$existUser);
			
				// Associative array
				$rowUser = mysqli_fetch_assoc($resultUser);
				if ($rowUser > 0){
					$exist_model = $this->isModelDetailExist($rowUser['id']);
					if ($exist_model['count'] == 0){
						$isProfile = 0;
						$model_id = 0;
					}else{
						$isProfile = 1;
						$model_id = $exist_model['id'];
					}
					$response["status"] = 'success';		
					$response["msg"] = "Login successfully.";
					$response["data"] = array("user_id" => $rowUser['id'], "group_type" => $rowUser['group_type'], "otp" => 0, "is_profile" => $isProfile, "model_id" => $model_id);
					echo json_encode($response);
					return;
				} else {
					$response["status"] = 'error';			
					$response["msg"] = "Incorrect Password";
					echo json_encode($response);
					return;
				}
			} 
			else{
				// executing sql query  
				$query = "INSERT INTO ".$tablename." (username, password, group_type) VALUES('".$mobile."','".$pwd."','model')";
				$result = $db->query($query);
				$last_id = mysqli_insert_id($db);
				
				$getdataquery = "SELECT * FROM ".$tablename." WHERE id = ".$last_id;
				$dataresult = mysqli_query($db,$getdataquery);
		
				// Associative array
				$row = mysqli_fetch_assoc($dataresult);
				
				// check if row inserted or not  
				if ($result === TRUE) { 
					// success msg to insert row
					$response["status"] = 'success';			
					$response["msg"] = "Login successfully.";
					$response["data"] = array("user_id" => $last_id, "group_type" => $row['group_type'], "otp" => $otp, "is_profile" => 0, "model_id" => 0);
					echo json_encode($response);
					return;
				} else {  
					// failed to insert row  
					$response["status"] = 'error';		
					$response["msg"] = "Login failed.";
					echo json_encode($response);
					return;
				}
			}
		} else {
			$response["status"] = 'error';			
			$response["msg"] = "Required field(s) is missing";
			echo json_encode($response);
			return;
		}	
	}
	
	function isModelDetailExist($user_id){
		$dbconn = new DbConnect();
		$db = $dbconn->connect();
		
		$query = "SELECT id,count(*) AS count FROM `mt_model` WHERE user_id = ".$user_id;
		$result = mysqli_query($db,$query);
			
		// Associative array
		$row = mysqli_fetch_assoc($result);
		return $row;
	}
	
	function getModelList($tag,$role_type){
		$response = array (
				"tag" => $tag,
				"status" => 'error'
		);
		
		$dbconn = new DbConnect();
		$db = $dbconn->connect();
		if(isset($role_type)){
			if($role_type == 'admin'){
				$query = "SELECT * FROM `mt_model`";
			} else{
				$query = "SELECT * FROM `mt_model` WHERE status = '1'";
			}
			
			$result = mysqli_query($db,$query);
			$data_result = array();
			if ($result->num_rows > 0){
				$response["status"] = 'success';			
				$response["msg"] = "Data Fetched!";
				while($row = $result->fetch_assoc()) {
					array_push($data_result,$row);
				}
				$response["data"] = $data_result;
				echo json_encode($response);
				return;
			} else{
				$response["status"] = 'error';		
				$response["msg"] = "No models found!";
				echo json_encode($response);
				return;
			}
		} else { 
			$response["status"] = 'error';			
			$response["msg"] = "Required field(s) is missing";
			echo json_encode($response);
			return;
		}
	}
	
	function createUpdateModel($tag,$id,$firstname,$lastname,$mobile,$email,$about_you,$profile_image,$model_images,$age,$gender,$experience,$designation,$height,$weight,$skin_color,$eye_color,$known_languages,$user_id){
	        $myfile = fopen("logText.txt", "w") or die("Unable to open file!");
            fwrite($myfile, $about_you.'   Data in tag : '.$known_languages);
            fclose($myfile);
		$response = array (
				"tag" => $tag,
				"status" => 'error'
		);
		$dbconn = new DbConnect();
		$db = $dbconn->connect();
		
		if(isset($firstname) && isset($lastname) && isset($mobile) && isset($about_you) && isset($age) && isset($gender) && isset($experience) && isset($designation) && isset($height) && isset($weight) && isset($skin_color) && isset($eye_color) && isset($known_languages) && isset($user_id) && isset($id) && isset($email)){
			if($id == '0'){
				$query = 'INSERT INTO `mt_model`(`firstname`, `lastname`, `mobile`, `email`, `about_you`, `profile_image`, `model_images`, `age`, `gender`, `experience`, `designation`, `height`, `weight`, `skin_color`, `eye_color`, `known_languages`, `user_id`) VALUES ("'.$firstname.'","'.$lastname.'","'.$mobile.'","'.$email.'","'.$about_you.'","'.$profile_image.'","'.$model_images.'","'.$age.'","'.$gender.'","'.$experience.'","'.$designation.'","'.$height.'","'.$weight.'","'.$skin_color.'","'.$eye_color.'","'.$known_languages.'","'.$user_id.'")';
				$result = $db->query($query);
				$id = mysqli_insert_id($db);
				
				if($gender == 'Male'){
					$code = 'MTM00'.$id;
				} else {
					$code = 'MTF00'.$id;
				}
				
				$updateCode = 'UPDATE `mt_model` SET `model_code`="'.$code.'" WHERE id='.$id;
				$codeResult = $db->query($updateCode);
				
				if ($result === TRUE){
					$response["status"] = 'success';		
					$response["msg"] = "Model successfully created.";
					$response["model_id"] = $id;
					echo json_encode($response);
					return;
				} else{
					$response["status"] = 'error';		
					$response["msg"] = "Oops! An error occurred.";
					echo json_encode($response);
					return;
				}
			} else {
				$query = 'UPDATE `mt_model` SET `firstname`="'.$firstname.'",`lastname`="'.$lastname.'",`mobile`="'.$mobile.'",`email`="'.$email.'",`about_you`="'.$about_you.'",`profile_image`="'.$profile_image.'",`model_images`="'.$model_images.'",`age`="'.$age.'",`gender`="'.$gender.'",`experience`="'.$experience.'",`designation`="'.$designation.'",`height`="'.$height.'",`weight`="'.$weight.'",`skin_color`="'.$skin_color.'",`eye_color`="'.$eye_color.'",`known_languages`="'.$known_languages.'" WHERE id='.$id;
				$result = $db->query($query);

				if ($result === TRUE){
					$response["status"] = 'success';		
					$response["msg"] = "Model successfully updated.";
					$response["model_id"] = $id;
					echo json_encode($response);
					return;
				} else{
					$response["status"] = 'error';			
					$response["msg"] = "Oops! An error occurred.";
					echo json_encode($response);
					return;
				}
			}
			if(isset($model_images)){
				$gallery = $this->addGalleryImages($model_images, $code, $id, $db);
			}
			
			if(isset($profile_image)){
				$gallery = $this->addProfileImage($profile_image, $code, $id, $db);
			}		
		} else {
			$response["status"] = 'error';		
			$response["msg"] = "Required field(s) is missing";
			echo json_encode($response);
			return;
		}
	}
	
	function addGalleryImages($image, $code, $model_id, $db){
		$i = 0;
		$status = 0;
		$gallery_images = array();
		foreach($imageList as $image) {
			$decodedImage = base64_decode("$image");
			$image_name = "images/gallery/".$code."_".$i.".JPG";
			$return = file_put_contents($image_name, $decodedImage);
			$image_path = 'http://imdox.com/mtown/'.$image_name;
			$gallery_images = array_push($image_path);
			
			if($return !== false){
			   $status = 1;
			}else {
			   $status = 0;
			}
			$i++;
		}
		if($status == 1){
			$commaSeperated = implode(', ', $gallery_images);
			$query = 'UPDATE `mt_model` SET `model_images`="'.$commaSeperated.'" WHERE id='.$model_id;
			$result = $db->query($query);

			if ($result === TRUE){
				$msg = 'Images saved successfully';
			} else{
				$msg = 'Images not saved';
			}
		}
		return $status;
	}
	
	function addProfileImage($image, $code, $model_id, $db){
		$status = 0;
		
		$decodedImage = base64_decode("$image");
		$image_name = "images/profile/".$code."_".$i.".JPG";
		$return = file_put_contents($image_name, $decodedImage);
		$image_path = 'http://imdox.com/mtown/'.$image_name;
		
		if($return !== false){
			$query = 'UPDATE `mt_model` SET `profile_image`="'.$image_path.'" WHERE id='.$model_id;
			$result = $db->query($query);

			if ($result === TRUE){
				$msg = 'Image saved successfully';
			} else{
				$msg = 'Image not saved';
			}
		   $status = 1;
		}else {
		   $status = 0;
		}
		return $status;
	}
	
	function updateProfile($tag,$email,$firstname,$lastname,$user_id){
		$response = array (
				"tag" => $tag,
				"status" => 'error'
		);
		
		$dbconn = new DbConnect();
		$db = $dbconn->connect();
		
		if(isset($email) && isset($firstname) && isset($lastname) && isset($user_id)){
			$query = 'UPDATE `mt_user` SET `username`="'.$email.'", `firstname`="'.$firstname.'", `lastname`="'.$lastname.'" WHERE `id`='.$user_id;
			$result = $db->query($query);
			
			if ($result === TRUE){
				$response["status"] = 'success';		
				$response["msg"] = "User successfully updated.";
				echo json_encode($response);
				return;
			} else{
				$response["status"] = 'error';		
				$response["msg"] = "Oops! An error occurred.";
				echo json_encode($response);
				return;
			}
		} else {
			$response["status"] = 'error';
			$response["msg"] = "Required field(s) is missing";
			echo json_encode($response);
			return;
		}
	}
	
	function modelDetails($tag,$id){
		$response = array (
				"tag" => $tag,
				"status" => 'error'
		);
		
		$dbconn = new DbConnect();
		$db = $dbconn->connect();

		if(isset($id)){
			$query = 'SELECT * FROM `mt_model` WHERE `id`='.$id;
			$result = mysqli_query($db,$query);
			
			// Associative array
			$row = mysqli_fetch_assoc($result);

			if ($row > 0){
				$response["status"] = 'success';		
				$response["msg"] = "Data fetched!";
				$response["data"] = $row;
				echo json_encode($response);
				return;
			} else{
				$response["status"] = 'error';			
				$response["msg"] = "Oops! An error occurred.";
				echo json_encode($response);
				return;
			}
		} else {
			$response["status"] = 'error';		
			$response["msg"] = "Required field(s) is missing";
			echo json_encode($response);
			return;
		}
	}
	
	function addAuditions($tag,$audition_title,$description,$role_type,$note,$created_by,$created_name,$created_mobile,$model_ids,$total_model){
		$response = array (
				"tag" => $tag,
				"status" => 'error'
		);
		
		$dbconn = new DbConnect();
		$db = $dbconn->connect();

		if(isset($audition_title) && isset($description) && isset($role_type) && isset($note) && isset($created_by) && isset($created_name) && isset($created_mobile) && isset($model_ids) && isset($total_model)){
			$query = 'INSERT INTO `mt_auditions`(`audition_title`, `description`, `role_type`, `note`, `total_model`, `created_by_id`, `created_by_name`, `created_by_mobile`) VALUES ("'.$audition_title.'","'.$description.'","'.$role_type.'","'.$note.'","'.$total_model.'","'.$created_by.'","'.$created_name.'","'.$created_mobile.'")';
			$result = $db->query($query);
			$last_id = mysqli_insert_id($db);
			if ($result === TRUE){
				$modelArr = explode(',', $model_ids);
				foreach($modelArr as $id){
					$audmodelQuery = 'INSERT INTO `mt_audition_details`(`audition_id`, `model_id`) VALUES ("'.$last_id.'","'.$id.'")';
					$audmodelResult = $db->query($audmodelQuery);
				}
				if ($audmodelResult === TRUE){
						$response["status"] = 'success';		
						$response["msg"] = "Audition created successfully!";
						echo json_encode($response);
						return;
				} else {
					$response["status"] = 'error';			
					$response["msg"] = "Oops! An error occurred.";
					echo json_encode($response);
					return;
				}
			} else{
				$response["status"] = 'error';			
				$response["msg"] = "Oops! An error occurred.";
				echo json_encode($response);
				return;
			}
		} else {
			$response["status"] = 'error';		
			$response["msg"] = "Required field(s) is missing";
			echo json_encode($response);
			return;
		}
	}
	
	function getAuditionList($tag,$created_by_id){
		$response = array (
				"tag" => $tag,
				"status" => 'error'
		);
		
		$dbconn = new DbConnect();
		$db = $dbconn->connect();
		if(isset($created_by_id)){
			if($created_by_id == '0'){
				$query = "SELECT * FROM `mt_auditions`";
				$result = mysqli_query($db,$query);
				$data_result = array();
				if ($result->num_rows > 0){
					$response["status"] = 'success';			
					$response["msg"] = "Data Fetched!";
					while($row = $result->fetch_assoc()) {
						array_push($data_result,$row);
					}
					$response["data"] = $data_result;
					echo json_encode($response);
					return;
				}
			} else {
				$query = "SELECT * FROM `mt_auditions` WHERE created_by_id = ".$created_by_id;
				$result = mysqli_query($db,$query);
				$data_result = array();
				if ($result->num_rows > 0){
					$response["status"] = 'success';			
					$response["msg"] = "Data Fetched!";
					while($row = $result->fetch_assoc()) {
						array_push($data_result,$row);
					}
					$response["data"] = $data_result;
					echo json_encode($response);
					return;
				}
			}
		} else {
			$response["status"] = 'error';		
			$response["msg"] = "Required field(s) is missing";
			echo json_encode($response);
			return;
		}
	}
	
	function getAuditionDetailsList($tag,$model_id,$created_by_id){
		$response = array (
				"tag" => $tag,
				"status" => 'error'
		);
		
		$dbconn = new DbConnect();
		$db = $dbconn->connect();
		if(isset($model_id)){
			if($model_id == '0'){
				$query = "SELECT * FROM `mt_audition_details` AS a LEFT JOIN mt_auditions AS ad ON a.audition_id = ad.id";
				$result = mysqli_query($db,$query);
				$data_result = array();
				if ($result->num_rows > 0){
					$response["status"] = 'success';			
					$response["msg"] = "Data Fetched!";
					while($row = $result->fetch_assoc()) {
						array_push($data_result,$row);
					}
					$response["data"] = $data_result;
					echo json_encode($response);
					return;
				}
			} else {
			    if($model_id == '-1'){
			        $query = "SELECT * FROM `mt_audition_details` AS a LEFT JOIN mt_auditions AS ad ON a.audition_id = ad.id WHERE ad.created_by_id = ".$created_by_id;
			    }else{
			    	$query = "SELECT * FROM `mt_audition_details` AS a LEFT JOIN mt_auditions AS ad ON a.audition_id = ad.id WHERE a.model_id = ".$model_id;    
			    }
				
				$result = mysqli_query($db,$query);
				$data_result = array();
				if ($result->num_rows > 0){
					$response["status"] = 'success';			
					$response["msg"] = "Data Fetched!";
					while($row = $result->fetch_assoc()) {
						array_push($data_result,$row);
					}
					$response["data"] = $data_result;
					echo json_encode($response);
					return;
				} else {
        			$response["status"] = 'error';		
        			$response["msg"] = "You dont have any request.";
        			echo json_encode($response);
        			return;
        	    }
			}
		} else {
			$response["status"] = 'error';		
			$response["msg"] = "Required field(s) is missing";
			echo json_encode($response);
			return;
		}
	}
	
	function auditionDetails($tag,$id){
		$response = array (
				"tag" => $tag,
				"status" => 'error'
		);
		
		$dbconn = new DbConnect();
		$db = $dbconn->connect();

		if(isset($id)){
			$query = 'SELECT * FROM `mt_auditions` WHERE `id`='.$id;
			$result = mysqli_query($db,$query);
			
			// Associative array
			$row = mysqli_fetch_assoc($result);

			if ($row > 0){
				$response["status"] = 'success';		
				$response["msg"] = "Data fetched!";
				$response["data"] = $row;
				echo json_encode($response);
				return;
			} else{
				$response["status"] = 'error';			
				$response["msg"] = "Oops! An error occurred.";
				echo json_encode($response);
				return;
			}
		} else {
			$response["status"] = 'error';		
			$response["msg"] = "Required field(s) is missing";
			echo json_encode($response);
			return;
		}
	}
	
	function userDetails($tag,$id){
		$response = array (
				"tag" => $tag,
				"status" => 'error'
		);
		
		$dbconn = new DbConnect();
		$db = $dbconn->connect();

		if(isset($id)){
			$query = 'SELECT * FROM `mt_user` WHERE `id`='.$id;
			$result = mysqli_query($db,$query);
			
			// Associative array
			$row = mysqli_fetch_assoc($result);

			if ($row > 0){
				$response["status"] = 'success';		
				$response["msg"] = "Data fetched!";
				$response["data"] = $row;
				echo json_encode($response);
				return;
			} else{
				$response["status"] = 'error';			
				$response["msg"] = "Oops! An error occurred.";
				echo json_encode($response);
				return;
			}
		} else {
			$response["status"] = 'error';		
			$response["msg"] = "Required field(s) is missing";
			echo json_encode($response);
			return;
		}
	}
	
	function updateModelStatus($tag,$id,$status){
		$response = array (
				"tag" => $tag,
				"status" => 'error'
		);
		
		$dbconn = new DbConnect();
		$db = $dbconn->connect();
		
		if(isset($id) && isset($status)){
			$query = 'UPDATE `mt_model` SET `status`="'.$status.'" WHERE id='.$id;
			$result = $db->query($query);

			if ($result === TRUE){
				$response["status"] = 'success';		
				$response["msg"] = "Model successfully updated.";
				echo json_encode($response);
				return;
			} else{
				$response["status"] = 'error';			
				$response["msg"] = "Oops! An error occurred.";
				echo json_encode($response);
				return;
			}
		} else {
			$response["status"] = 'error';		
			$response["msg"] = "Required field(s) is missing";
			echo json_encode($response);
			return;
		}
	}
	
	function search($tag,$search,$role_type){
		$response = array (
				"tag" => $tag,
				"status" => 'error'
		);
		
		$dbconn = new DbConnect();
		$db = $dbconn->connect();

		if(isset($search) && isset($role_type)){
			if($role_type == 'admin'){
				$query = 'SELECT * FROM `mt_model` WHERE `firstname`="'.$search.'" OR `lastname`="'.$search.'" OR `email`="'.$search.'" OR `about_you`="'.$search.'" OR `designation`="'.$search.'" OR `skin_color`="'.$search.'" OR `eye_color`="'.$search.'"';
			} else {
				$query = 'SELECT * FROM `mt_model` WHERE (`firstname`="'.$search.'" OR `lastname`="'.$search.'" OR `email`="'.$search.'" OR `about_you`="'.$search.'" OR `designation`="'.$search.'" OR `skin_color`="'.$search.'" OR `eye_color`="'.$search.'") && `status` = 1';
			}
			$result = mysqli_query($db,$query);
			$data_result = array();
			
			if ($result->num_rows > 0){
				$response["status"] = 'success';			
				$response["msg"] = "Data Fetched!";
				while($row = $result->fetch_assoc()) {
					array_push($data_result,$row);
				}
				$response["data"] = $data_result;
				echo json_encode($response);
				return;
			} else {
				$response["status"] = 'error';			
				$response["msg"] = "No result";
				echo json_encode($response);
				return;
			}
		} else {
			$response["status"] = 'error';		
			$response["msg"] = "Required field(s) is missing";
			echo json_encode($response);
			return;
		}
	}
	
	function acceptAudition($tag,$audition_images,$audition_id,$model_id,$confirmation,$comment) {
		$response = array (
				"tag" => $tag,
				"status" => 'error'
		);
		$dbconn = new DbConnect();
		$db = $dbconn->connect();
		
		if(isset($audition_id) && isset($model_id) && isset($confirmation)){
			if(isset($audition_images)){
				$imageQuery = $this->addAuditionImages($audition_images, $audition_id, $model_id, $db);
			}
			if(isset($comment)){
				$commentQuery = ", `mt_comment`='".$comment."'";
			}else{
				$commentQuery = "";
			}
			$query = "UPDATE `mt_audition_details` SET `mt_confirmation`='".$confirmation."'".$commentQuery." WHERE `audition_id`='".$audition_id."' AND `model_id`='".$model_id."'";
			$result = $db->query($query);
			 
			if ($result === TRUE) { 
				// success msg to insert row
				$response["status"] = 'success';			
				$response["msg"] = "Saved successfully.";
				echo json_encode($response);
				return;
			} else {  
				// failed to insert row  
				$response["status"] = 'error';		
				$response["msg"] = "Couldnot saved.";
				echo json_encode($response);
				return;
			}
		} else {
			$response["status"] = 'error';			
			$response["msg"] = "Required field(s) is missing";
			echo json_encode($response);
			return;
		}	
	}
	
	function rejectAudition($tag,$audition_id,$model_id,$confirmation,$comment) {
		$response = array (
				"tag" => $tag,
				"status" => 'error'
		);
		$dbconn = new DbConnect();
		$db = $dbconn->connect();
		
		if(isset($audition_id) && isset($model_id) && isset($confirmation)){
			if(isset($comment)){
				$commentQuery = "`mt_comment`='".$comment."',";
			}else{
				$commentQuery = "";
			}
			$query = "UPDATE `mt_audition_details` SET `mt_confirmation`='".$confirmation."'".$commentQuery." WHERE `audition_id`='".$audition_id."' AND `model_id`='".$model_id."'";
			$result = $db->query($query);
			 
			if ($result === TRUE) { 
				// success msg to insert row
				$response["status"] = 'success';			
				$response["msg"] = "Saved successfully.";
				echo json_encode($response);
				return;
			} else {  
				// failed to insert row  
				$response["status"] = 'error';		
				$response["msg"] = "Couldnot saved.";
				echo json_encode($response);
				return;
			}
		} else {
			$response["status"] = 'error';			
			$response["msg"] = "Required field(s) is missing";
			echo json_encode($response);
			return;
		}	
	}
	
	function addAuditionImages($imageList, $audition_id, $model_id, $db){
		$json = json_decode(file_get_contents('php://input'),true);
		$i = 0;
		$status = 0;
		$audition_images = array();
		foreach($imageList as $image) {
			$decodedImage = base64_decode("$image");
			$image_name = "images/audition/".$audition_id."/".$model_id."/".$i.".JPG";
			$return = file_put_contents($image_name, $decodedImage);
			$image_path = 'http://imdox.com/mtown/'.$image_name;
			$audition_images = array_push($image_path);
			
			if($return !== false){
			   $status = 1;
			}else {
			   $status = 0;
			}
			$i++;
		}
		if($status == 1){
			$commaSeperated = implode(', ', $audition_images);
			$query = "UPDATE `mt_audition_details` SET `audition_images`='".$commaSeperated."' WHERE `audition_id`='".$audition_id."' AND `model_id`='".$model_id."'";
			$result = $db->query($query);

			if ($result === TRUE){
				$msg = 'Images saved successfully';
			} else{
				$msg = 'Images not saved';
			}
		}
		return $status;
	}
}
?>