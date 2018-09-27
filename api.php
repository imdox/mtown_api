<?php  
error_reporting ( 0 );
include 'functionapi.php';

if (isset ($_POST ['tag'] ) && $_POST ['tag'] != '') {
	$tag = $_POST ['tag'];
	$functionClass = new FunctionHelper();
	if ($tag == 'login') {
		$mobile = $_POST ['mobile'];
		$password = $_POST ['password'];
		$functionClass->Login($tag,$mobile,$password);
	}else if ($tag == 'modellist') {
		$role_type = $_POST ['role_type'];
		$functionClass->getModelList($tag,$role_type);
	}else if ($tag == 'update_profile') {
		$email = $_POST ['email'];
		$firstname = $_POST ['firstname'];
		$lastname = $_POST ['lastname'];
		$user_id = $_POST ['user_id'];
		$functionClass->updateProfile($tag,$email,$firstname,$lastname,$user_id);
	}else if ($tag == 'model_details') {
		$id = $_POST ['id'];
		$functionClass->modelDetails($tag,$id);
	}else if ($tag == 'user_details') {
		$id = $_POST ['id'];
		$functionClass->userDetails($tag,$id);
	}else if ($tag == 'add_audition') {
	    $audition_title = $_POST ['audition_title'];
		$description = $_POST ['description'];
		$role_type = $_POST ['role_type'];
		$note = $_POST ['note'];
		$created_by = $_POST ['created_by'];
		$model_ids = $_POST ['model_ids'];
		$created_name= $_POST ['created_name'];
		$created_mobile= $_POST ['created_mobile'];
		$total_model= $_POST ['total_model'];
		$functionClass->addAuditions($tag,$audition_title,$description,$role_type,$note,$created_by,$created_name,$created_mobile,$model_ids,$total_model);
	}else if ($tag == 'audition_list') {
		$created_by_id = $_POST ['created_by_id'];
		$functionClass->getAuditionList($tag,$created_by_id);
	}else if ($tag == 'audition_details_list') {
		$model_id = $_POST ['model_id'];
		$created_by_id = $_POST ['created_by_id'];
		$functionClass->getAuditionDetailsList($tag,$model_id,$created_by_id);
	}else if ($tag == 'audition_details') {
		$id = $_POST ['id'];
		$functionClass->auditionDetails($tag,$id);
	}else if ($tag == 'update_model_status') {
		$id = $_POST ['id'];
		$status = $_POST ['status'];
		$functionClass->updateModelStatus($tag,$id,$status);
	}else if ($tag == 'search') {
		$search = $_POST ['search'];
		$role_type = $_POST ['role_type'];
		$functionClass->search($tag,$search,$role_type);
	}else if ($tag == 'update_audition_detail') {
		$audition_images = $_POST ['audition_images'];
		$audition_id = $_POST ['audition_id'];
		$model_id = $_POST ['model_id'];
		$confirmation = $_POST ['confirmation'];
		$comment = $_POST ['comment'];
		$functionClass->acceptAudition($tag,$audition_images,$audition_id,$model_id,$confirmation,$comment);
	}else if ($tag == 'reject_audition_detail') {
		$audition_id = $_POST ['audition_id'];
		$model_id = $_POST ['model_id'];
		$confirmation = $_POST ['confirmation'];
		$comment = $_POST ['comment'];
		$functionClass->rejectAudition($tag,$audition_id,$model_id,$confirmation,$comment);
	}
}else{
    $json = json_decode(file_get_contents('php://input'),true);
    if (isset ($json ['tag'] ) && $json ['tag'] != '') {
	    $tag = $json ['tag'];
        if ($tag == 'create_update_model'){
    		$id = $json ['id'];
    		$firstname = $json ['firstname'];
    		$lastname = $json ['lastname'];
    		$mobile = $json ['mobile'];
    		$email = $json ['email'];
    		$about_you = $json ['about_you'];
    		$profile_image = $json ['profile_image'];
    		$model_images = $json ['model_images'];
    		$age = $json ['age'];
    		$gender = $json ['gender'];
    		$experience = $json ['experience'];
    		$designation = $json ['designation'];
    		$height = $json ['height'];
    		$weight = $json ['weight'];
    		$skin_color = $json ['skin_color'];
    		$eye_color = $json ['eye_color'];
    		$known_languages = $json ['known_languages'];
    		$user_id = $json ['user_id'];
    		$issue = $functionClass->createUpdateModel($tag,$id,$firstname,$lastname,$mobile,$email,$about_you,$profile_image,$model_images,$age,$gender,$experience,$designation,$height,$weight,$skin_color,$eye_color,$known_languages,$user_id);
    	}
    }
}
?>  

